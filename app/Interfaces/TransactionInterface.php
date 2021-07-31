<?php

namespace App\Interfaces;

use App\Models\Transaction;
use App\Support\Currency;
use App\Support\TransactionCollection;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

interface TransactionInterface
{

   /**
     * Use a custom Collection for transactions.
     *
     * @param  array  $models
     * @return TransactionCollection
     */
    public function newCollection(array $models = []): TransactionCollection;

    /**
     * The token this transaction applies to
     * 
     * @return BelongsTo
     */
    public function token(): BelongsTo; 


    /**
     * The total value of this transaction
     * 
     * @return Currency  
     */
    public function total(): Currency;

    /**
     * Is this a buy transaction ?
     * 
     * @return bool
     */
    public function isBuy(): bool;

    /**
     * Is this a buy transaction ?
     * 
     * @return bool
     */
    public function isSell(): bool;

    /**
     * Replicate this transaction with the original ID
     * 
     * @return Transaction
     */
    public function replicateWithId(): Transaction;

    /**
     * Related transactions
     * Buy order - find the transaction where these were sold
     * Sell order - find the transactions where these were puchased
     * 
     * @return TransactionCollection
     */
    public function related(): TransactionCollection;

}
