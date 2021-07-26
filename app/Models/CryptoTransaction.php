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
     * Related transactions
     * Buy order - find the transaction where these were sold
     * Sell order - find the transactions where these were puchased
     * 
     * @return TransactionCollection
     */
    public function related(): TransactionCollection
    {
        $related = new TransactionCollection();

        if( $this->isSell() )
        {
            // Unsold transactions (or part) at time of this transaction
            $unsold = $this->cryptoToken->transactions->unsoldTransactions( $this->time );

            // This order will sell the oldest of them
            $toBuy = $this->quantity;

            foreach($unsold->sortBy('time') as $transaction)
            {
                $newTrans = $transaction->replicate();    
                $newTrans->id = $transaction->id;      
                $newTrans->hodlDays = $this->time->diffInDays($transaction->time);      

                // Transaction less or equal to the amount needed - keep it whole transction
                if( $transaction->quantity->getValue() <= $toBuy->getValue() )
                {
                    $newTrans->profitLoss = $this->price->multiply($newTrans->quantity)->subtract($newTrans->total());
               

                    $related->push( $newTrans );

                    $toBuy = $toBuy->subtract($transaction->quantity);

                    if( $toBuy->getValue() <= 0 ) break;

                }
                // Transaction quantity larger than amount needed - subtract amound and keep
                elseif( $toBuy->getValue() > 0 )
                {
                    $newTrans->quantity = $toBuy;

                    $newTrans->profitLoss = $this->price->multiply($newTrans->quantity)->subtract($newTrans->total());

                    $related->push( $newTrans );

                    $toBuy = new Quantity(0.0);
                }
            }

        }
        else
        {
            // balance before
            $balanceBefore = $this->cryptoToken->balance( $this->time );

            // total bought
            $toSell = $this->quantity;

            // balance after
            $balanceAfter = $this->cryptoToken->balance( $this->time->addSeconds(1) );

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
                if($sell->quantity->getValue() <= $amountToIgnore->getValue())
                {
                    // ignore it / remove it

                    $amountToIgnore = $amountToIgnore->subtract($sell->quantity);
                }
                else if($amountToIgnore->getValue() > 0)
                {
                    // Part sold so we update and keep it
                    $sell->quantity = $sell->quantity->subtract($amountToIgnore);

                    $sell->profitLoss = $sell->total()->subtract($this->price->multiply($sell->quantity));

                    $tmpSells->push($sell);
                }
                else
                {
                    $sell->profitLoss = $sell->total()->subtract($this->price->multiply($sell->quantity));
                    // just store it
                    $tmpSells->push($sell);
                }
            }

            // This collection is now all the sell orders that could have sold our buy order, oldest first....
            foreach( $tmpSells->sortBy('time') as $sell )
            {
                $sell->hodlDays = $sell->time->diffInDays($this->time);

                if($sell->quantity->getValue() <= $toSell->getValue() && $toSell->getValue() > 0 )
                {
                    // just store it
                    $related->push($sell);

                    $toSell = $toSell->subtract($sell->quantity);
                }
                else if($toSell->getValue() > 0)
                {
                    // Part sold so we update and keep it
                    $sell->quantity = $toSell;
                    $related->push($sell);

                    $toSell = new Quantity(0);
                }
                else
                {
                    // ignore it
                }          
            }


            
        }

        return $related->sortByDesc('time');

    }
}
