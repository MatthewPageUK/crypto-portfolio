<?php

namespace App\Rules;

use Illuminate\Contracts\Validation\Rule;

class ValidTransactionsRule implements Rule
{
    /**
     * Create a new rule instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Determine if the validation rule passes, this just passes
     * through the result of transactions->isValid
     *
     * @param  string  $attribute
     * @param  mixed  $value
     * @return bool
     */
    public function passes($attribute, $value)
    {
        return $value;
    }

    /**
     * Get the validation error message.
     *
     * @return string
     */
    public function message()
    {
        return 'This edit will cause a negative balance error.';
    }
}
