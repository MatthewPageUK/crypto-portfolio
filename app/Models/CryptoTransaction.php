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
        $unsold = $this->cryptoToken->transactions->unsoldTransactions( $this->time );
        $toBuy = $this->quantity;

        foreach($unsold->sortBy('time') as $transaction)
        {
            $newTrans = $transaction->replicateWithId();    
            $newTrans->hodlDays = $this->time->diffInDays($transaction->time);
            $newTrans->profitLoss = $this->price->multiply($newTrans->quantity)->subtract($newTrans->total());

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
        // balance before
        $balanceBefore = $this->cryptoToken->balance( $this->time );

        // total bought
        $toSell = $this->quantity;

        // every sell order after this order
        $sells = $this->cryptoToken->transactions
            ->where('type', CryptoTransaction::SELL)
            ->where('time', '>', $this->time)
            ->sortByDesc('time');

        // skip sells up to total quantity == balance before (they sell the preivous balance)
        $amountToIgnore = $balanceBefore;
        $tmpSells = new TransactionCollection();

        foreach($sells as $sell)
        {
            $sell->profitLoss = $sell->total()->subtract($this->price->multiply($sell->quantity));
            $sell->hodlDays = $sell->time->diffInDays($this->time);

            if($sell->quantity->lte( $amountToIgnore ) )
            {
                // ignore it / remove it
                $amountToIgnore = $amountToIgnore->subtract($sell->quantity);
            }
            else if($amountToIgnore->gt(0) )
            {
                // Part sold so we update and keep it
                $sell->quantity = $sell->quantity->subtract($amountToIgnore);
                $tmpSells->push($sell);
            }
            else
            {
                // just store it
                $tmpSells->push($sell);
            }
        }

        // This collection is now all the sell orders that could have sold our buy order, oldest first....
        foreach( $tmpSells->sortBy('time') as $sell )
        {
            if( $sell->quantity->lte( $toSell ) && $toSell->gt(0) )
            {
                // just store it
                $related->push($sell);
                $toSell = $toSell->subtract($sell->quantity);
            }
            else if( $toSell->gt(0) )
            {
                // Part sold so we update and keep it
                $sell->quantity = $toSell;
                $related->push($sell);
                $toSell = new Quantity(0);
            }     
        }
        
        return $related;
    }
}
