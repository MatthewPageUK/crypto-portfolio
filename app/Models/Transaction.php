<?php

namespace App\Models;

use App\Interfaces\TransactionInterface;
use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Support\TransactionCollection;
use App\Support\Currency;
use App\Support\Quantity;

use App\Support\Casts\CurrencyCast;
use App\Support\Casts\QuantityCast;
use App\Support\Presenters\TransactionPresenter;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Transaction extends Model implements TransactionInterface
{
    use HasFactory;
    use SoftDeletes;
    use HasTimestamps;
    use TransactionPresenter;

    const BUY = 'buy';
    const SELL = 'sell';

    public int $hodlDays = 0;
    public Currency $profitLoss;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'token_id',
        'quantity',
        'price',
        'type',
        'note',
        'time',
    ];

    protected $casts = [
        'time' => 'datetime',
        'price' => CurrencyCast::class,
        'quantity' => QuantityCast::class,
    ];

    /**
     * Use a custom Collection for transactions.
     *
     * @param  array  $models
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public function newCollection(array $models = []): TransactionCollection
    {
        return new TransactionCollection($models);
    }

    /**
     * The token this transaction applies to
     */
    public function token(): BelongsTo
    {
        return $this->belongsTo(Token::class);
    }

    /**
     * The total value of this transaction
     *
     * @return Currency
     */
    public function total(): Currency
    {
        return $this->price->multiply( $this->quantity );
    }

    /**
     * Is this a buy transaction ?
     */
    public function isBuy(): bool
    {
        return ( $this->type === Transaction::BUY ) ? true : false;
    }

    /**
     * Is this a buy transaction ?
     */
    public function isSell(): bool
    {
        return ( $this->type === Transaction::SELL ) ? true : false;
    }

    /**
     * Replicate this transaction with the original ID
     * ak Clone ?
     *
     * @return Transaction
     */
    public function replicateWithId(): Transaction
    {
        $transaction = $this->replicate();
        $transaction->id = $this->id;

        return $transaction;
    }

    /**
     * Related transactions
     * Buy order - find the transaction where these were sold
     * Sell order - find the transactions where these were puchased
     *
     * @return TransactionCollection
     */
    public function related(): TransactionCollection
    {
        return app('related-transactions')->relatedTo( $this );
    }

}
