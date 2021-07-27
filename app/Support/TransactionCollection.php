<?php

namespace App\Support;

use App\Models\CryptoTransaction;
use Illuminate\Support\Collection;
use Carbon\Carbon;
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
     * Add up the values of supplied key and return a Currency
     */
    public function sumCurrency( string $key ): Currency
    {
        return new Currency( $this->sumNumber( $key ) );
    }
    /**
     * Add up the values of supplied key and return a Quantity
     */
    public function sumQuantity( string $key ): Quantity
    {
        return new Quantity( $this->sumNumber( $key ) );
    }

    /**
     * Add up the values of supplied key and return a Number
     */
    private function sumNumber( string $key ): Number
    {
        $total = new Number();
        foreach( $this as $transaction )
        {
            if( $key === 'total' )
                $total = $total->add( $transaction->total() );
            else
                $total = $total->add( $transaction->$key );
        }
        return $total;
    }

    /**
     * Return the balance from stored value or fresh calculation
     * 
     * @param Carbon $at            Return balance at this date
     * @param bool $recalculate     Force the calculation to rerun
     * @return float                The balance
     */
    public function balance(  $at = null, $recalculate = true ): Quantity
    {
        if( is_null($this->balance) || $recalculate ) $this->balance = $this->calcBalance( $at );

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
                if( $transaction->isBuy() )
                    $balance = $balance->add( $transaction->quantity );
                else
                    $balance = $balance->subtract( $transaction->quantity );
            }
            else
            {
                return $balance;
            }
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
                $total = $total->add($transaction->total());
                $quantity = $quantity->add($transaction->quantity);
            }
        }

        return new Currency( ( $total->gt(0) && $quantity->gt(0) ) ? $total->divide($quantity) : 0.0);
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
    private function validateTransactions( ?Quantity $balance = null ): bool
    {
        if( is_null( $balance ) ) $balance = new Quantity();

        foreach( $this->sortBy('time') as $transaction )
        {
            if( $transaction->isBuy() )
                $balance = $balance->add( $transaction->quantity );
            else
                $balance = $balance->subtract( $transaction->quantity );

            if( $balance->lt(0) ) return false;
        }

        return true;
    }

    /**
     * Unsold transactions
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

        foreach( $this->where('type', CryptoTransaction::BUY)->sortByDesc('time') as $transaction )
        {
            if( $at > $transaction->time )
            {
                $newTrans = $transaction->replicateWithId();

                if( $unsoldQuantity->lt( $transaction->quantity ) ) $newTrans->quantity = new Quantity( $unsoldQuantity->getValue() );

                $unsoldQuantity = $unsoldQuantity->subtract( $newTrans->quantity );

                if( $newTrans->quantity->gt(0) ) $unsoldTransactions->push( $newTrans );

                if( $unsoldQuantity->lte(0) ) break;
            }
        }

        return $unsoldTransactions;
    }
}
