<?php

namespace App\Support;

use App\Support\Presenters\QuantityPresenter;

/**
 * A class for storing crypto token quanties, from very small to very large
 * 
 */
class Quantity 
{
    use QuantityPresenter;

    private float $value = 0;

    /**
     * Create a new Quantity instance
     * 
     * @param float $value      The value / quantity being passed
     */
    public function __construct( $value = 0 )
    {
        $this->value = $value;
    }

    /**
     * Get the raw value
     * 
     * @return float
     */
    public function getValue()
    {
        return $this->value;
    }

    /**
     * Set the raw value
     * 
     * @param float $value      The value / quantity to set
     */
    public function setValue( $value )
    {
        $this->value = $value;
    }
    
}
