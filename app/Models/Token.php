<?php

namespace App\Models;

use App\Support\Currency;
use App\Support\Quantity;
use App\Interfaces\TokenInterface;

use Illuminate\Database\Eloquent\Concerns\HasTimestamps;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Token extends Model implements TokenInterface
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
     *
     * @return HasMany
     */
    public function transactions(): HasMany
    {
        return $this->hasMany(Transaction::class)->orderByDesc('time');
    }

    /**
     * Bots trading this token
     *
     * @return HasMany
     */
    public function bots(): HasMany
    {
        return $this->hasMany(Bot::class);
    }

    /**
     * The balance for this token
     *
     * @param Carbon $at        Return the balance at this date
     * @return Quantity
     */
    public function balance( $at = null ): Quantity
    {
        return $this->transactions->balance( $at );
    }

    /**
     * Average price this token was purchased at
     */
    public function averageBuyPrice(): Currency
    {
        return $this->transactions->averageBuyPrice();
    }

    /**
     * Average price of this token being held / unsold
     */
    public function averageHodlBuyPrice(): Currency
    {
        return $this->transactions->averageHodlBuyPrice();
    }

    /**
     * Average price this token was sold at
     */
    public function averageSellPrice(): Currency
    {
        return $this->transactions->averageSellPrice();
    }

}
