<?php

namespace App\Support;

use App\Models\CryptoTransaction;
use Illuminate\Support\Collection;

/**
 * A collection of transactions and logic to calculate 
 * averages and the overall balance. 
 * 
 */
class TransactionCollection extends Collection
{

    /**
     * Calculate the balance of token from all the transactions.
     * 
     * @param float     $startingBalance    Optional starting balance
     * @return float                        The current balance of tokens
     */
    public function calcBalance(float $startingBalance = 0): float
    {
        $balance = $startingBalance;

        $balance += $this->sum( function (CryptoTransaction $transaction) {
            return ( $transaction['type']==='buy' ) ? $transaction->quantity : -$transaction->quantity;
        });

        return $balance;
    }

    /**
     * Calculate the average price of the speicifed type transactions.
     * 
     * @param string $type                          Transaction type / buy or sell
     * @param Collection | Array $transactions      Optional collection, overides using this->items
     * @return float                                The average price of all the buy transactions
     */
    private function calcAveragePrice( $type, $transactions = null ): float
    {
        $total = 0;
        $quantity = 0;

        $transactions = ($transactions) ? $transactions : $this->items;
        
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
        return $this->calcAveragePrice('buy');
    }

    /**
     * Average sell price for this token
     * 
     * @return float    The average price of all the sell transactions
     */
    public function averageSellPrice(): float
    {
        return $this->calcAveragePrice('sell');
    }

    /**
     * Average hodl buy price
     * 
     * The average buy price of tokens that are still being held. Sell orders will 
     * remove tokens from the calculation - rule 'sell oldest tokens first'
     * 
     * Todo - all works with good data, but $unsold[0] can be null in certain conditions..
     * Todo - error checking for negative balance
     * 
     * @return float    The average price tokens being held
     */
    public function averageHodlBuyPrice(): float
    {
        $unsold = [];
        $sorted = $this->sortBy('time');

        foreach($sorted as $transaction)
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











    /**
     * Replay transaction list
     * Todo
     * Replays all the transactions in order and fails if it results in 
     * a negative balance or other error.
     */
}