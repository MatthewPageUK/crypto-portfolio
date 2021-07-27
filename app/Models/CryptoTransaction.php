<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Support\TransactionCollection;
use App\Support\Currency;
use App\Support\Quantity;

use App\Support\Casts\CurrencyCast;
use App\Support\Casts\QuantityCast;
use App\Support\Presenters\TransactionPresenter;

class CryptoTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTimestamps;
    use TransactionPresenter;

    const BUY = 'buy';
    const SELL = 'sell';

    public int $hodlDays = 0;
    public Currency $profitLoss;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'crypto_token_id',
        'quantity',
        'price',
        'type',
        'time',
    ];

    protected $casts = [
        'time' => 'datetime',
        'price' => CurrencyCast::class,
        'quantity' => QuantityCast::class,
    ];

    /**
     * Use a custom Collection for transactions.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = [])
    {
        return new TransactionCollection($models);
    }

    /**
     * The token this transaction applies to
     */
    public function cryptoToken()
    {
        return $this->belongsTo(CryptoToken::class);
    }

    /**
     * The total value of this transaction
     * 
     * @return Currency  
     */
    public function total(): Currency
    {
        return $this->price->multiply( $this->quantity );
    }

    /**
     * Is this a buy transaction ?
     */
    public function isBuy(): bool
    {
        return ( $this->type === CryptoTransaction::BUY ) ? true : false;
    }

    /**
     * Is this a buy transaction ?
     */
    public function isSell(): bool
    {
        return ( $this->type === CryptoTransaction::SELL ) ? true : false;
    }

    /**
     * Replicate this transaction with the original ID
     * ak Clone ?
     * 
     * @return CryptoTransaction
     */
    public function replicateWithId(): CryptoTransaction
    {
        $transaction = $this->replicate();
        $transaction->id = $this->id;
        
        return $transaction;
    }

    /**
     * Related transactions
     * Buy order - find the transaction where these were sold
     * Sell order - find the transactions where these were puchased
     * 
     * This is achieved by replaying the transaction list in order, it seems to work
     * but not easy to explain :)
     * 
     * @return TransactionCollection
     */
    public function related(): TransactionCollection
    {
        $related = new TransactionCollection();

        if( $this->isSell() )
        {
            $related = $this->relatedBuyOrders($related);
        }
        else
        {
            $related = $this->relatedSellOrders($related);
        }

        return $related->sortByDesc('time');
    }

    /**
     * Get the Buy orders related to this Sell order.
     * Find out where and when we bought the tokens we are selling
     * 
     * @param TransactionCollection $related        Empty collection
     * @return TransactionCollection
     */
    private function relatedBuyOrders( $related ): TransactionCollection
    {
        // Unsold transactions (or part) at time of this transaction
        // This Sell order will sell the oldest of them
        // todo comments
        $unsold = $this->cryptoToken->transactions->unsoldTransactions( $this->time );
        $toBuy = $this->quantity;

        foreach($unsold->sortBy('time') as $transaction)
        {
            $newTrans = $transaction->replicateWithId();    

            // Transaction less or equal to the amount needed - keep whole transction
            if( $transaction->quantity->lte( $toBuy ) )
            {               
                $related->push( $newTrans );
                $toBuy = $toBuy->subtract($transaction->quantity);
                if( $toBuy->lte(0) ) break;

            }
            // Transaction quantity larger than amount needed - subtract amound and keep part order
            elseif( $toBuy->gt(0) )
            {
                $newTrans->quantity = $toBuy;
                $related->push( $newTrans );
                $toBuy = new Quantity(0.0);
            }
        }
        /**
         * Update the stat fields for the related transactions
         */
        foreach($related as $transaction)
        {
            $transaction->hodlDays = $this->time->diffInDays($transaction->time);
            $transaction->profitLoss = $this->price->multiply($transaction->quantity)->subtract($transaction->total());
        }
        
        return $related;
    }

    /**
     * Get the Sell orders related to this Buy order.
     * Find out where and when we sell the tokens we have bought, 
     * or if we are still hodling some or all.
     * 
     * @param TransactionCollection $related        Empty collection
     * @return TransactionCollection
     */
    private function relatedSellOrders( $related ): TransactionCollection
    {
        /**
         * First deal with the existing balanceBefore
         * These tokens need to be sold before our new ones can be
         */
        $balanceBefore = $this->cryptoToken->balance( $this->time );

        /**
         * Every sell order after this buy order
         * If our tokens have been sold it's in here
         */
        $sells = $this->cryptoToken->transactions
            ->where('type', CryptoTransaction::SELL)
            ->where('time', '>', $this->time)
            ->sortBy('time');

        $possibleSells = new TransactionCollection();

        foreach($sells as $sell)
        {
            /**
             * Store some info for later use
             */
            $sell->profitLoss = $sell->total()->subtract($this->price->multiply($sell->quantity));
            $sell->hodlDays = $sell->time->diffInDays($this->time);

            /**
             * We have sold some of the balanceBefore, subtract quantity sold
             * and ignore the transaction, we don't need it now
             */
            if($sell->quantity->lte( $balanceBefore ) )
            {
                $balanceBefore = $balanceBefore->subtract( $sell->quantity );
            }
            /**
             * We trying sell more than balanceBefore and we still have a balanceBefore
             * Adjust the transaction to only sell the remainder, this part order then 
             * gets added to our possibleSells list and results in a zero balanceBefore
             */
            else if($balanceBefore->gt(0) )
            {
                $sell->quantity = $sell->quantity->subtract( $balanceBefore );
                $balanceBefore = $balanceBefore->subtract( $sell->quantity );
                $possibleSells->push( $sell );
            }
            /**
             * balanceBefore has been zeroed and all subsequent transactions are
             * added to our possibleSells, these could have sold our new Buy order.
             */
            else
            {
                $possibleSells->push($sell);
            }
        }

        /**
         * possibleSells is now all the sell orders that could have sold our buy order.
         */
        $toSell = $this->quantity;

        foreach( $possibleSells->sortBy('time') as $sell )
        {
            /**
             * This order sells less than our target toSell, keep it and 
             * subtract its quantity from toSell
             */
            if( $sell->quantity->lte( $toSell ) && $toSell->gt(0) )
            {
                $related->push($sell);
                $toSell = $toSell->subtract($sell->quantity);
            }
            /**
             * This order wants to sell more than we have, adjust the quantity
             * and keep it and break out.
             */
            else if( $toSell->gt(0) )
            {
                $sell->quantity = $toSell;
                $related->push($sell);
                $toSell = new Quantity(0);

                break;
            }     
        }
        
        /**
         * Final collection of orders that have sold our buy order :) 
         */
        return $related;
    }
}
