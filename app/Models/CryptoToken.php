<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class CryptoToken extends Model
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
        'name',
        'symbol',
    ];

    /**
     * Calculated balance
     */
    public $balance = 0;
    public $avgBuyPrice = 0;

    /**
     * Update the token balance on retrieval
     */
    public static function boot()
    {
        parent::boot();
        static::retrieved(function($model)
        {
            $model->updateBalance();
        });
    }

    /**
     * Transactions for this token
     */
    public function transactions()
    {
        return $this->hasMany(CryptoTransaction::class);
    }

    /**
     * Update the balance from transactions
     */
    public function updateBalance()
    {
        $balance = 0;
        foreach($this->transactions  as $transaction)
        {
            $balance += ( $transaction->type === "buy" ) ? $transaction->quantity : -$transaction->quantity;
        }
        $this->balance = $balance;
    }
}
