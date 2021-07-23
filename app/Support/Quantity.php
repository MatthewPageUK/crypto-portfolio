<?php

namespace App\Support;

/**
 * A class for storing crypto token quanties, from very small to very large
 * 
 */
class Quantity 
{

    private float $value = 0;

    public function __construct( $value = 0 )
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
        return number_format($this->value);
    }
}
