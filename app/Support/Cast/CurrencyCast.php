<?php

namespace App\Support\Cast;

use Illuminate\Contracts\Database\Eloquent\CastsAttributes;
use App\Support\Currency;

/**
 * Class for storing Fiat amounts
 */
class CurrencyCast implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return array
     */
    public function get($model, $key, $value, $attributes)
    {
        return new Currency( $value );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return string
     */
    public function set($model, $key, $value, $attributes)
    {
        return ( $value instanceOf Currency ) ? $value->get() : $value;
    }
}
