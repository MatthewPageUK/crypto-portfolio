<?php

namespace App\Support;

/**
 * Class for storing Fiat amounts
 */
class Currency
{

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
    public function get(): float
    {
        return $this->value;
    }

    /**
     * Set the raw value
     * 
     * @param float $value      The value / amount to set
     */
    public function set( $value ): void
    {
        $this->value = $value;
    }

    /**
     * Return a human readable representation of this amount
     * 
     * @return string
     */
    public function humanReadable(): string
    {
        $amount = $this->value;
        $text = $amount;
        $pre = "";

        if($amount <= 0.0001 && $amount !== 0) 
        {
            $pre = "< ";
            $text = "0.0001";
        }

        if($amount < 0.1 && $amount > 0.0001) $text = number_format($amount, 6, '.', '');
        if($amount >= 0.1 && $amount < 1) $text = number_format($amount, 5, '.', '');
        if($amount >= 1 && $amount < 10) $text = number_format($amount, 4, '.', '');
        if($amount >= 10 && $amount < 1000) $text = number_format($amount, 2, '.', '');
        if($amount >= 1000) $text = number_format($amount, 0, '.', '');

        return $pre . Currency::SYMBOL . $text;
    }
}
