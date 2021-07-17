<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CryptoTransaction extends Model
{
    use HasFactory;
    use SoftDeletes;
    use HasTimestamps;

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
    ];

    /**
     * The token this transaction applies to
     */
    public function cryptoToken()
    {
        return $this->belongsTo(CryptoToken::class);
    }

    /**
     * The total value of this transaction
     */
    public function total()
    {
        return $this->quantity * $this->price;
    }
}
