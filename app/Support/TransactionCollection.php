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
     * Validate transactions the by processing them one at 
     * a time in Time ASC order.
     * 
     * Return False if the balance is ever negative.
     * 
     * @return bool    
     */
    public function validateTransactions(): bool
    {
        $balance = 0;
        $sorted = $this->sortBy('time');

        foreach( $sorted as $transaction )
        {
            $balance += ( $transaction->type === 'buy' ) ? $transaction->quantity : -$transaction->quantity;
            if( $balance < 0 ) return false;
        }

        return true;
    }

    /**
     * Average hodl buy price
     * 
     * The average buy price of tokens that are still being held - rule 'sell oldest tokens first'
     * 
     * @return float    The average price tokens being held
     */
    public function averageHodlBuyPrice(): float
    {
        $unsold = [];
        // $sorted = $this->sortDesc('time');
        $balance = $this->calcBalance();

        if($balance == 0 ) return 0.00;

        foreach($this->items as $transaction)
        {
            if($transaction->type === 'buy')
            {
                if($balance >= $transaction->quantity)
                {
                    $unsold[] = $transaction;
                    $balance -= $transaction->quantity;
                }
                else
                {
                    $transaction->quantity -= $balance;
                    $unsold[] = $transaction;
                    $balance = 0;
                }
            }
            if($balance == 0) break;
        }

        return $this->calcAveragePrice('buy', $unsold);
    }

}
