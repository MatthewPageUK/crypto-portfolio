<?php

namespace App\Support;

use App\Models\Transaction;
use Illuminate\Support\Collection;
use Carbon\Carbon;
use App\Support\Quantity;
use App\Support\Currency;
use App\Interfaces\TransactionCollectionInterface;

/**
 * A collection of transactions and logic to calculate 
 * averages and the overall balance. 
 * 
 */
class TransactionCollection extends Collection implements TransactionCollectionInterface
{

    /**
     * Store the calculated balance for later use
     */
    private ?Quantity $balance = null;

    /**
     * Unsold transactions / buy orders
     * 
     * Returns collection of transactions that have not been sold or part sold
     * Rule - sell oldest tokens first
     * 
     * @param Carbon $at                Return unsold transactions at date 
     * @return TransactionCollection    The unsold transactions
     */
    public function unsoldTransactions( Carbon $at = null ): TransactionCollection
    {
        $at = is_null($at) ? Carbon::now() : $at;
        $unsoldTransactions = new TransactionCollection();
        $unsoldQuantity = $this->balance( $at );

        foreach( $this->where('type', Transaction::BUY)->sortByDesc('time') as $transaction )
        {
            if( $at > $transaction->time )
            {
                /**
                 * Create a copy of the transaction, we may change the quantity
                 */
                $newTrans = $transaction->replicateWithId();

                /**
                 * Transaction sells more than we have, change its quantity to what is needed (unsoldQuantity)
                 */
                if( $unsoldQuantity->lt( $transaction->quantity ) ) $newTrans->quantity = new Quantity( $unsoldQuantity->getValue() );

                /**
                 * Remove the transaction amount from unsoldQuantity
                 */
                $unsoldQuantity = $unsoldQuantity->subtract( $newTrans->quantity );

                /**
                 * If the new transaction has a quantity, keep it
                 */
                if( $newTrans->quantity->gt(0) ) $unsoldTransactions->push( $newTrans );

                /**
                 * If we have assigned all unsoldQuantity break out
                 */
                if( $unsoldQuantity->lte(0) ) break;
            }
        }

        /**
         * Return collection of unsold buy orders.
         */
        return $unsoldTransactions;
    }

    /**
     * Add up the values of supplied key and return a Currency
     * 
     * @param string $key       The key to sum up
     * @return Currency
     */
    public function sumCurrency( string $key ): Currency
    {
        return new Currency( $this->sumNumber( $key ) );
    }
    /**
     * Add up the values of supplied key and return a Quantity
     * 
     * @param string $key       The key to sum up
     * @return Quantity
     */
    public function sumQuantity( string $key ): Quantity
    {
        return new Quantity( $this->sumNumber( $key ) );
    }

    /**
     * Return the balance from stored value or fresh calculation
     * 
     * @param Carbon $at            Return balance at this date
     * @param bool $recalculate     Force the calculation to rerun
     * @return Quantity                The balance
     */
    public function balance(  $at = null, $recalculate = true ): Quantity
    {
        if( is_null($this->balance) || $recalculate ) $this->balance = $this->calcBalance( $at );

        return $this->balance;
    }

   /**
     * Average buy price for this token
     * 
     * @return Currency    The average price of all the buy transactions
     */
    public function averageBuyPrice(): Currency
    {
        return $this->calcAveragePrice( Transaction::BUY );
    }

    /**
     * Average sell price for this token
     * 
     * @return Currency  
     */
    public function averageSellPrice(): Currency
    {
        return $this->calcAveragePrice( Transaction::SELL );
    }

    /**
     * The average buy price of tokens that are still being held
     * 
     * @return Currency    
     */
    public function averageHodlBuyPrice(): Currency
    {
        return $this->unsoldTransactions()->averageBuyPrice();
    }

    /**
     * Is the chain of transactions valid ?
     * 
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->validateTransactions();
    }

    /**
     * Add up the values of supplied key and return a Number
     * 
     * @param string $key       The key to sum up
     * @return Number
     */
    private function sumNumber( string $key ): Number
    {
        $total = new Number();
        foreach( $this as $transaction )
        {
            /**
             * If key name is 'total' we need to call a method or just access the key
             */
            if( $key === 'total' )
                $total = $total->add( $transaction->total() );
            else
                $total = $total->add( $transaction->$key );
        }
        return $total;
    }

    /**
     * Calculate the final balance of all transactions as of an optional
     * 'at' date or now().
     * 
     * @param Carbon       $at                 Return balance at this date
     * @param Quantity     $balance            Optional starting balance
     * @return Quantity                        The current balance of tokens
     */
    private function calcBalance( ?Carbon $at = null, ?Quantity $balance = null ): Quantity
    {
        if( is_null( $balance ) ) $balance = new Quantity();
        if( is_null( $at ) ) $at = Carbon::now();

        foreach( $this->sortBy('time') as $transaction )
        {
            if( $transaction->time < $at )
            {
                $balance = $this->modifyBalance( $balance, $transaction );
            }
            else
            {
                return $balance;
            }
        }
        return $balance;
    }

    /**
     * Modify the balance by adding or subtracting the supplied transaction
     * quantity.
     * 
     * @param Quantity $balance                     The start balance
     * @param Transaction $transaction              The transaction
     * @return Quantity                             Balance after transaction
     */
    private function modifyBalance( Quantity $balance, Transaction $transaction )
    {
        return $transaction->isBuy() ? $balance->add( $transaction->quantity ) : $balance->subtract( $transaction->quantity );
    }

    /**
     * Calculate the average price of the speicifed type of transactions.
     * 
     * @param string $type                          Transaction type / buy or sell
     * @param Currency $total                       Starting total
     * @param Quantity $quantity                    Starting quantity
     * @return Currency                             The average price of all the buy transactions
     */
    private function calcAveragePrice( $type = Transaction::BUY, Currency $total = null, Quantity $quantity = null ): Currency
    {
        if( is_null( $total ) ) $total = new Currency();
        if( is_null( $quantity ) ) $quantity = new Quantity();

        foreach( $this as $transaction )
        {
            if( $transaction->type === $type )
            {
                $total = $total->add($transaction->total());
                $quantity = $quantity->add( $transaction->quantity );
            }
        }

        return new Currency( $total->divide( $quantity ) );
    }

    /**
     * Validate transactions by processing them one at 
     * a time in Time ASC order.
     * 
     * Return False if the balance is ever negative.
     * 
     * @param Quantity     $balance    Starting balance
     * @return bool    
     */
    private function validateTransactions( ?Quantity $balance = null ): bool
    {
        if( is_null( $balance ) ) $balance = new Quantity();

        foreach( $this->sortBy('time') as $transaction )
        {
            $balance = $this->modifyBalance( $balance, $transaction );
            if( $balance->lt(0) ) return false;
        }

        return true;
    }

}
