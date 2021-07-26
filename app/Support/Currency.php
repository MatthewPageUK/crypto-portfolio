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
     * @param float|Number $value      The value / amount being passed
     */
    public function __construct( $value = 0 )
    {
        $this->value = ( $value instanceof Number ) ? $value->getValue() : $value;
    }

}
