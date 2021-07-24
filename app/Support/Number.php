<?php

namespace App\Support;

class Number
{

    /**
     * The raw value of this number
     */
    public float $value = 0;

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

    /**
     * Multiply this value with supplied value
     * Returns class type of the orinal class, not the multiplier
     * 
     * @param Number $number        Number instance to multiply by
     * @return mixed
     */
    public function multiply(Number $number)
    {
        $class = get_class($this);
        $result = $this->getValue() * $number->getValue();
        return new $class($result);
    }

    /**
     * Add this value with supplied value
     * Returns class type of the orinal class, not the multiplier
     * 
     * @param Number $number        Number instance to add
     * @return mixed
     */
    public function add(Number $number)
    {
        $class = get_class($this);
        $result = $this->getValue() + $number->getValue();
        $this->value = $result;
        return new $class($result);
    }

    /**
     * Subtract supplied value from this
     * Returns class type of the orinal class, not the multiplier
     * 
     * @param Number $number        Number instance to add
     * @return mixed
     */
    public function subtract(Number $number)
    {
        $class = get_class($this);
        $result = $this->getValue() - $number->getValue();
        $this->value = $result;
        return new $class($result);
    }
}