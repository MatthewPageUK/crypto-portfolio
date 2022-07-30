<?php

namespace App\Interfaces;

interface PriceOracleInterface
{
    /**
     * Get the price from this oracle
     *
     * @return float
     * @throws PriceOracleFailureException
     */
    public function getPrice(): float;

}
