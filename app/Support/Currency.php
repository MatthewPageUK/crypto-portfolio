<?php

namespace App\Support;

/**
 * Class for storing Fiat amounts
 */
class Currency
{

    const SYMBOL = '£';

    private float $value = 0;

    public function __construct( $value )
    {
        $this->value = $value;
    }

    public function get()
    {
        return $this->value;
    }

    public function set( $value )
    {
        $this->value = $value;
    }

    public function human()
    {
        return Currency::SYMBOL . number_format($this->value);
    }
}
