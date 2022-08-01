<?php

namespace App\Interfaces;

use App\Support\Quantity;
use App\Support\Currency;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface TokenInterface
{

    /**
     * Transactions for this token
     *
     * @return HasMany
     */
    public function transactions(): HasMany;

    /**
     * Bots trading this token
     *
     * @return HasMany
     */
    public function bots(): HasMany;

    /**
     * The balance for this token
     *
     * @param Carbon $at        Return the balance at this date
     * @return Quantity
     */
    public function balance( $at = null ): Quantity;

    /**
     * Average price this token was purchased at
     *
     * @return Currency
     */
    public function averageBuyPrice(): Currency;

    /**
     * Average price of this token being held / unsold
     *
     * @return Currency
     */
    public function averageHodlBuyPrice(): Currency;

    /**
     * Average price this token was sold at
     *
     * @return Currency
     */
    public function averageSellPrice(): Currency;

}
