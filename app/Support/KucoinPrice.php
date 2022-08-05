<?php

namespace App\Support;

use KuCoin\SDK\PublicApi\Symbol;

use App\Exceptions\PriceOracleFailureException;
use App\Interfaces\PriceOracleInterface;

class KucoinPrice implements PriceOracleInterface
{
    /**
     *
     * @return float
     * @throws PriceOracleFailureException
     */
    public function getPrice(): float
    {
        try {
            $api = new Symbol();
            $data = $api->getTicker('VET-USDT');
        } catch (\Exception $e) {
            throw new PriceOracleFailureException($e->getMessage());
        }

        if(! $data['price'] > 0) {
            throw new PriceOracleFailureException('Invalid response in Price data');
        }

        return $data['price'];
    }
}
