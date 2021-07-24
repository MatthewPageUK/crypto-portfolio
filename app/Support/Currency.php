<?php

namespace App\Support;

use App\Support\Presenters\CurrencyPresenter;

/**
 * Class for storing Fiat amounts
 */
class Currency
{
    use CurrencyPresenter;

    const SYMBOL = '£';

    private float $value = 0;

    /**
     * Create a new Currency instance
     * 
     * @param float $value      The value / amount being passed
     */
    public function __construct( $value )
    {
        $this->value = $value;
    }

    /**
     * Get the raw value
     * 
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * Set the raw value
     * 
     * @param float $value      The value / amount to set
     */
    public function setValue( $value ): void
    {
        $this->value = $value;
    }
    
}
