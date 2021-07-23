<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

use App\Support\TransactionCollection;
use App\Support\Currency;
use App\Support\Cast\CurrencyCast;
use App\Support\Cast\QuantityCast;

class CryptoTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTimestamps;

    const BUY = 'buy';
    const SELL = 'sell';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'crypto_token_id',
        'quantity',
        'price',
        'type',
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
    public function newCollection(array $models = [])
    {
        return new TransactionCollection($models);
    }

    /**
     * The token this transaction applies to
     */
    public function cryptoToken()
    {
        return $this->belongsTo(CryptoToken::class);
    }

    /**
     * The total value of this transaction
     * 
     * @return Currency  
     */
    public function total(): Currency
    {
        return new Currency( $this->quantity->get() * $this->price->get() );
    }

    /**
     * Is this a buy transaction ?
     */
    public function isBuy(): bool
    {
        return ( $this->type === CryptoTransaction::BUY ) ? true : false;
    }

    /**
     * Is this a buy transaction ?
     */
    public function isSell(): bool
    {
        return ( $this->type === CryptoTransaction::SELL ) ? true : false;
    }
}
