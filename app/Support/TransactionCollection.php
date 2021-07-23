<?php

namespace App\Support;

use App\Models\CryptoTransaction;
use Illuminate\Support\Collection;
use App\Support\Quantity;
use App\Support\Currency;

/**
 * A collection of transactions and logic to calculate 
 * averages and the overall balance. 
 * 
 */
class TransactionCollection extends Collection
{

    /**
     * Store the calculated balance for later use
     */
    private float $balance = -1;

    /**
     * Return the balance from stored value or fresh calculation
     * 
     * @param bool $recalculate     Force the calculation to rerun
     * @return float                The balance
     */
    public function balance( $recalculate = false ): float
    {
        if( $this->balance == -1 || $recalculate ) $this->balance = $this->calcBalance();

        return $this->balance;
    }

   /**
     * Average buy price for this token
     * 
     * @return float    The average price of all the buy transactions
     */
    public function averageBuyPrice(): Currency
    {
        return new Currency( $this->calcAveragePrice( CryptoTransaction::BUY ) );
    }

    /**
     * Average sell price for this token
     * 
     * @return float  
     */
    public function averageSellPrice(): Currency
    {
        return new Currency( $this->calcAveragePrice( CryptoTransaction::SELL ) );
    }

    /**
     * The average buy price of tokens that are still being held
     * 
     * @return float    
     */
    public function averageHodlBuyPrice(): Currency
    {
        return $this->unsoldTransactions()->averageBuyPrice();
    }

    /**
     * Is the chain of transactions valid ?
     */
    public function isValid(): bool
    {
        return $this->validateTransactions();
    }

    /**
     * Calculate the final balance of all transactions.
     * 
     * @param float     $balance            Optional starting balance
     * @return float                        The current balance of tokens
     */
    private function calcBalance( float $balance = 0 ): float
    {
        $balance += $this->sum( function( CryptoTransaction $transaction ) {
            return ( $transaction['type'] === CryptoTransaction::BUY ) ? $transaction->quantity->get() : -$transaction->quantity->get();
        });

        return $balance;
    }

    /**
     * Calculate the average price of the speicifed type transactions.
     * 
     * @param string $type                          Transaction type / buy or sell
     * @param float $total                          Starting total
     * @param float $quantity                       Starting quantity
     * @return float                                The average price of all the buy transactions
     */
    private function calcAveragePrice( $type = CryptoTransaction::BUY, $total = 0, $quantity = 0 ): float
    {
        foreach($this->items as $transaction)
        {
            if($transaction->type === $type)
            {
                $total += $transaction->total()->get();
                $quantity += $transaction->quantity->get();
            }
        }

        return ($total > 0 && $quantity > 0) ? $total / $quantity : 0.0;
    }

    /**
     * Validate transactions by processing them one at 
     * a time in Time ASC order.
     * 
     * Return False if the balance is ever negative.
     * 
     * @param float     $balance    Starting balance
     * @return bool    
     */
    private function validateTransactions( $balance = 0 ): bool
    {
        $sorted = $this->sortBy('time');

        foreach( $sorted as $transaction )
        {
            $balance += ( $transaction->isBuy() ) ? $transaction->quantity->get() : -$transaction->quantity->get();
            if( $balance < 0 ) return false;
        }

        return true;
    }

    /**
     * Unsold transactions
     * 
     * Returns collection of transactions that have not been sold or part sold
     * Rule - sell oldest tokens first
     * 
     * Todo - should be sorted by time desc
     * 
     * @return TransactionCollection    The unsold transactions
     */
    private function unsoldTransactions(): TransactionCollection
    {
        $unsoldTransactions = new TransactionCollection();
        $unsoldQuantity = $this->balance();

        foreach( $this->where('type', CryptoTransaction::BUY) as $transaction )
        {
            $transaction->quantity = new Quantity( ( $unsoldQuantity < $transaction->quantity->get() ) ? $unsoldQuantity : $transaction->quantity->get() );
            $unsoldQuantity -= $transaction->quantity->get();

            if( $transaction->quantity->get() > 0 ) $unsoldTransactions->push( $transaction );

            if( $unsoldQuantity == 0 ) break;
        }
        return $unsoldTransactions;
    }
}
