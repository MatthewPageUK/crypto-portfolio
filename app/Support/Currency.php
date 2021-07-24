<?php

namespace App\Support;

use App\Support\Presenters\CurrencyPresenter;

/**
 * Class for storing Fiat amounts
 */
class Currency extends Number
{
    use CurrencyPresenter;

    const SYMBOL = '£';

    /**
     * Create a new Currency instance
     * 
     * @param float $value      The value / amount being passed
     */
    public function __construct( float $value = 0 )
    {
        $this->value = $value;
    }

}
