<?php

namespace App\Support;

use App\Models\Transaction;

class RelatedTransactions
{
    public function __construct()
    {

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
    public function relatedTo( Transaction $transaction ): TransactionCollection
    {
        if( $transaction->isSell() )
        {
            $related = $this->relatedBuyOrders( $transaction );
        }
        else
        {
            $related = $this->relatedSellOrders( $transaction );
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
    private function relatedBuyOrders( $parent ): TransactionCollection
    {

        $related = new TransactionCollection();

        // Unsold transactions (or part) at time of this transaction
        // This Sell order will sell the oldest of them
        // todo comments
        $unsold = $parent->token->transactions->unsoldTransactions( $parent->time );
        $toBuy = $parent->quantity;

        foreach($unsold->sortBy('time') as $transaction)
        {
            $newTrans = $transaction->replicateWithId();    

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
        /**
         * Update the stat fields for the related transactions
         */
        foreach($related as $transaction)
        {
            $transaction->hodlDays = $parent->time->diffInDays($transaction->time);
            $transaction->profitLoss = $parent->price->multiply($transaction->quantity)->subtract($transaction->total());
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
    private function relatedSellOrders( $parent ): TransactionCollection
    {

        $related = new TransactionCollection();

        /**
         * First deal with the existing balanceBefore
         * These tokens need to be sold before our new ones can be
         */
        $balanceBefore = $parent->token->balance( $parent->time );

        /**
         * Every sell order after this buy order
         * If our tokens have been sold it's in here
         */
        $sells = $parent->token->transactions
            ->where('type', Transaction::SELL)
            ->where('time', '>', $parent->time)
            ->sortBy('time');

        $possibleSells = new TransactionCollection();

        /**
         * The balanceBefore must be sold before we can sell our buy order.
         * Remove all or part of the sell orders until balanceBefore is zero
         */
        foreach($sells as $sell)
        {
            /**
             * We have sold some of the balanceBefore, subtract quantity sold
             * and ignore the transaction, we don't need it now
             */
            if($sell->quantity->lte( $balanceBefore ) )
            {
                $balanceBefore = $balanceBefore->subtract( $sell->quantity );
            }
            /**
             * We trying sell more than balanceBefore and we still have a balanceBefore
             * Adjust the transaction to only sell the remainder, this part order then 
             * gets added to our possibleSells list and results in a zero balanceBefore
             */
            else if($balanceBefore->gt(0) )
            {
                $sell->quantity = $sell->quantity->subtract( $balanceBefore );
                $balanceBefore = $balanceBefore->subtract( $sell->quantity );
                $possibleSells->push( $sell );
            }
            /**
             * balanceBefore has been zeroed and all subsequent transactions are
             * added to our possibleSells, these could have sold our new Buy order.
             */
            else
            {
                $possibleSells->push($sell);
            }
        }

        /**
         * possibleSells is now all the sell orders that could have sold our buy order.
         */
        $toSell = $parent->quantity;

        foreach( $possibleSells->sortBy('time') as $sell )
        {
            /**
             * This order sells less than our target toSell, keep it and 
             * subtract its quantity from toSell
             */
            if( $sell->quantity->lte( $toSell ) && $toSell->gt(0) )
            {
                $related->push($sell);
                $toSell = $toSell->subtract($sell->quantity);
            }
            /**
             * This order wants to sell more than we have, adjust the quantity
             * and keep it and break out.
             */
            else if( $toSell->gt(0) )
            {
                $sell->quantity = $toSell;
                $related->push($sell);
                $toSell = new Quantity(0);

                break;
            }     
        }

        /**
         * Update the stat fields for the related transactions
         */
        foreach($possibleSells as $sell)
        {
            $sell->hodlDays = $sell->time->diffInDays($parent->time);
            $sell->profitLoss = $sell->total()->subtract($parent->price->multiply($sell->quantity));
        }      

        /**
         * Final collection of orders that have sold our buy order :) 
         */
        return $related;
    }

}
