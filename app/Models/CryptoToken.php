<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

class CryptoToken extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTimestamps;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'symbol',
    ];

    /**
     * Calculated balance
     */
    public $balance = 0;

    /**
     * Update the token balance on retrieval
     */
    public static function boot()
    {
        parent::boot();
        static::retrieved(function($model)
        {
            $model->updateBalance();
        });
    }

    /**
     * Transactions for this token
     */
    public function transactions()
    {
        return $this->hasMany(CryptoTransaction::class)->orderByDesc('time');
    }

    /**
     * Update the balance from transactions
     */
    public function updateBalance(): void
    {
        $balance = 0;
        foreach($this->transactions as $transaction)
        {
            $balance += ( $transaction->type === "buy" ) ? $transaction->quantity : -$transaction->quantity;
        }
        $this->balance = $balance;
    }




    /**
     * Average price from collection of transactions
     * 
     * @param EloquentCollection $transactions  Collection of transactions
     * @param string $type  Transaction type / buy or sell
     * @return float    The average price of all the buy transactions
     */
    private function calcAveragePrice( $type, $transactions ): float
    {
        $total = 0;
        $quantity = 0;

        foreach($transactions as $transaction)
        {
            if($transaction->type === $type)
            {
                $total += $transaction->quantity * $transaction->price;
                $quantity += $transaction->quantity;
            }
        }

        return ($total > 0 && $quantity > 0) ? $total / $quantity : 0.0;
    }

    /**
     * Average buy price for this token
     * 
     * @return float    The average price of all the buy transactions
     */
    public function averageBuyPrice(): float
    {
        return $this->calcAveragePrice('buy', $this->transactions);
    }

    /**
     * Average sell price for this token
     * 
     * @return float    The average price of all the sell transactions
     */
    public function averageSellPrice(): float
    {
        return $this->calcAveragePrice('sell', $this->transactions);
    }

    /**
     * Average hodl buy price
     * 
     * The average buy price of tokens that are still being held. Sell orders will 
     * remove tokens from the calculation - rule 'sell oldest tokens first'
     * 
     * @return float    The average price tokens being held
     */
    public function averageHodlBuyPrice()
    {
        $unsold = [];
        $transactions = $this->transactions()->reorder('time')->get();
        
        foreach($transactions as $transaction)
        {
            if($transaction->type === 'buy')
            {
                $unsold[] = $transaction;
            }
            else
            {
                /* Remove the sold tokens from the unsold array */

                $quantityToSell = $transaction->quantity;

                /* While there are still unallocated sell tokens */
                while($quantityToSell > 0)
                {
                    /* While the oldest buy transaction is less than unallocated */
                    while($quantityToSell > $unsold[0]->quantity)
                    {
                        /* Deduct the transaction amount and remove from unsold array, we've sold it */
                        $quantityToSell -= $unsold[0]->quantity;
                        $unsold = array_slice($unsold, 1);
                    }
                    /* Deduct final amount from first unsold transaction */
                    if($quantityToSell > 0)
                    {
                        $unsold[0]->quantity -= $quantityToSell;
                        $quantityToSell = 0;
                    }
                }
            }
        }

        return $this->calcAveragePrice('buy', $unsold);
    }

}
