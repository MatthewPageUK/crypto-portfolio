<?php

namespace App\Interfaces;

use App\Support\Quantity;
use App\Support\Currency;
use App\Support\TransactionCollection;
use Carbon\Carbon;

interface TransactionCollectionInterface
{
    /**
     * Add up the values of supplied key and return a Currency
     * 
     * @param string $key       The key to sum up
     * @return Currency
     */
    public function sumCurrency( string $key ): Currency;

    /**
     * Add up the values of supplied key and return a Quantity
     * 
     * @param string $key       The key to sump up
     * @return Quantity
     */
    public function sumQuantity( string $key ): Quantity;

    /**
     * Return the balance from stored value or fresh calculation
     * 
     * @param Carbon $at            Return balance at this date
     * @param bool $recalculate     Force the calculation to rerun
     * @return float                The balance
     */
    public function balance(  $at = null, $recalculate = true ): Quantity;

   /**
     * Average buy price for this token
     * 
     * @return Currency    The average price of all the buy transactions
     */
    public function averageBuyPrice(): Currency;

    /**
     * Average sell price for this token
     * 
     * @return Currency  
     */
    public function averageSellPrice(): Currency;
    
    /**
     * The average buy price of tokens that are still being held
     * 
     * @return Currency    
     */
    public function averageHodlBuyPrice(): Currency;

    /**
     * Is the chain of transactions valid ?
     */
    public function isValid(): bool;

    /**
     * Unsold transactions
     * 
     * Returns collection of transactions that have not been sold or part sold
     * Rule - sell oldest tokens first
     * 
     * @param Carbon $at                Return unsold transactions at date 
     * @return TransactionCollection    The unsold transactions
     */
    public function unsoldTransactions( Carbon $at = null ): TransactionCollection;

}
