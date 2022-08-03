<?php

namespace App\Interfaces;

use App\Models\Bot;
use App\Models\Price;
use App\Support\Quantity;
use App\Support\Currency;
use Illuminate\Database\Eloquent\Relations\HasMany;

interface BotBrainInterface
{
    /**
     * Constructor
     *
     * @param Bot $bot      The Bot instance
     * @return void
     */
    public function __construct(Bot $bot);

    /**
     * Process the price
     *
     * @param Price $price
     * @return void
     */
    public function processPrice(Price $price);


}
