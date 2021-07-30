<?php

namespace App\Support;

/**
 * Ready for BCMath to be dropped in when needed...
 */
class Number
{

    /**
     * The raw value of this number
     */
    public float $value = 0;

    /**
     * Constructor
     * 
     * @param float $value      Default value to start with
     */
    public function __construct( float $value = 0 )
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

    /**
     * Equal
     * 
     * @param mixed int|float|Number $number        Number instance to compare to
     * @return bool
     */
    public function eq($number): bool
    {
        if( ! $number instanceOf Number ) $number = new Number( $number );

        return $this->getValue() == $number->getValue();
    }    
    /**
     * Greater Than
     * 
     * @param mixed int|float|Number $number        Number instance to compare to
     * @return bool
     */
    public function gt($number): bool
    {
        if( ! $number instanceOf Number ) $number = new Number( $number );

        return $this->getValue() > $number->getValue();
    }    
    /**
     * Greater Than or Equal
     * 
     * @param mixed int|float|Number $number        Number instance to compare to
     * @return bool
     */
    public function gte($number): bool
    {
        if( ! $number instanceOf Number ) $number = new Number( $number );

        return $this->getValue() >= $number->getValue();
    }  
    /**
     * Less Than
     * 
     * @param mixed int|float|Number $number        Number instance to compare to
     * @return bool
     */
    public function lt($number): bool
    {
        if( ! $number instanceOf Number ) $number = new Number( $number );

        return $this->getValue() < $number->getValue();
    }
    /**
     * Less Than or Equal
     * 
     * @param mixed int|float|Number $number        Number instance to compare to
     * @return bool
     */
    public function lte($number): bool
    {
        if( ! $number instanceOf Number ) $number = new Number( $number );

        return $this->getValue() <= $number->getValue();
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
     * Divide this value by supplied value
     * Returns class type of the orinal class, not the divider
     * 
     * @param Number $number        Number instance to divide by
     * @return mixed
     */
    public function divide(Number $number)
    {
        $class = get_class($this);
        $result = ( ! $this->eq(0) && ! $number->eq(0) ) ?
            $this->getValue() / $number->getValue() : 
            0;
            
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
        return new $class($result);
    }
}