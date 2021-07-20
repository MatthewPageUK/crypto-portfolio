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
     * Transactions for this token
     */
    public function transactions()
    {
        return $this->hasMany(CryptoTransaction::class)->orderByDesc('time');
    }

    /**
     * The balance for this token
     */
    public function getBalance(): float
    {
        return $this->transactions()->get()->calcBalance();
    }

}
