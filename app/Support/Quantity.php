<?php

namespace App\Support;

use App\Support\Presenters\QuantityPresenter;

/**
 * A class for storing crypto token quanties, from very small to very large
 * 
 */
class Quantity extends Number
{
    use QuantityPresenter;

    /**
     * Create a new Quantity instance
     * 
     * @param float|Number $value      The value / quantity being passed
     */
    public function __construct( $value = 0 )
    {
        $this->value = ( $value instanceof Number ) ? $value->getValue() : $value;
    }

}
