<?php

namespace App\Exceptions;

use Exception;
use Illuminate\Support\Facades\Log;

class PriceOracleFailureException extends Exception
{
    /**
     * Report or log an exception.
     *
     * @return void
     */
    public function report()
    {
        Log::alert('Failed to get price : '.$this->getMessage());
    }
}
