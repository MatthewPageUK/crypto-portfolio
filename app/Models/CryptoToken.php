<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Facades\DB;

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
        return $this->hasMany(CryptoTransaction::class)->orderByDesc('time');
    }

    /**
     * Update the balance from transactions
     */
    public function updateBalance()
    {
        $balance = 0;
        foreach($this->transactions as $transaction)
        {
            $balance += ( $transaction->type === "buy" ) ? $transaction->quantity : -$transaction->quantity;
        }
        $this->balance = $balance;
    }

    /**
     * Average buy price
     */
    public function averagePrice()
    {
        // $quantity = DB::table((new CryptoTransaction())->getTable())
        //     ->where('crypto_token_id', $this->id)
        //     ->where('type', 'buy')
        //     ->sum('quantity');

        // $total = DB::table((new CryptoTransaction())->getTable())
        //     ->where(function ($query) {
        //         $query->selectRaw('price * quantity as total')
        //             ->from((new CryptoTransaction())->getTable())
        //             ->where('crypto_token_id', $this->id)
        //             ->where('type', 'buy');
        //     })
        //     ->avg('total');

        // return $total / $quantity;
        
        return 0.00;
    }
}
