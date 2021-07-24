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
    private ?Quantity $balance = null;

    /**
     * Return the balance from stored value or fresh calculation
     * 
     * @param bool $recalculate     Force the calculation to rerun
     * @return float                The balance
     */
    public function balance( $recalculate = false ): Quantity
    {
        if( is_null($this->balance) || $recalculate ) $this->balance = $this->calcBalance();

        return $this->balance;
    }

   /**
     * Average buy price for this token
     * 
     * @return float    The average price of all the buy transactions
     */
    public function averageBuyPrice(): Currency
    {
        return $this->calcAveragePrice( CryptoTransaction::BUY );
    }

    /**
     * Average sell price for this token
     * 
     * @return float  
     */
    public function averageSellPrice(): Currency
    {
        return $this->calcAveragePrice( CryptoTransaction::SELL );
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
     * @param Quantity     $balance            Optional starting balance
     * @return Quantity                        The current balance of tokens
     */
    private function calcBalance( Quantity $balance = null ): Quantity
    {
        if( is_null( $balance ) ) $balance = new Quantity();

        foreach( $this as $transaction )
        {
            if( $transaction->isBuy() )
                $balance->add( $transaction->quantity );
            else
                $balance->subtract( $transaction->quantity );
        }

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
    private function calcAveragePrice( $type = CryptoTransaction::BUY, Currency $total = null, Quantity $quantity = null ): Currency
    {
        if( is_null( $total ) ) $total = new Currency();
        if( is_null( $quantity ) ) $quantity = new Quantity();

        foreach( $this as $transaction )
        {
            if( $transaction->type === $type )
            {
                $total->add($transaction->total());
                $quantity->add($transaction->quantity);
            }
        }

        // todo lt eg gt divide
        return new Currency(($total->getValue() > 0 && $quantity->getValue() > 0) ? $total->getValue() / $quantity->getValue() : 0.0);
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
            $balance += ( $transaction->isBuy() ) ? $transaction->quantity->getValue() : -$transaction->quantity->getValue();
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
            $newTrans = $transaction->replicate();

            $newTrans->quantity = new Quantity( ( $unsoldQuantity->getValue() < $transaction->quantity->getValue() ) ? $unsoldQuantity->getValue() : $transaction->quantity->getValue() );
            $unsoldQuantity->setValue($unsoldQuantity->getValue() - $newTrans->quantity->getValue());

            if( $newTrans->quantity->getValue() > 0 ) $unsoldTransactions->push( $newTrans );

            if( $unsoldQuantity->getValue() == 0 ) break;
        }
        return $unsoldTransactions;
    }
}
