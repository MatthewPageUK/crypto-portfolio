<?php

namespace App\Support;

use App\Exceptions\PriceOracleFailureException;
use App\Interfaces\PriceOracleInterface;

class Coinmarketcap implements PriceOracleInterface
{
    private $url = 'https://pro-api.coinmarketcap.com';

    // Nasty hacky throw it in code ...

    /**
     *
     * @return float
     * @throws PriceOracleFailureException
     */
    public function getPrice(): float
    {
        $url = $this->url . '/v1/cryptocurrency/quotes/latest';

        $parameters = [
            'convert' => 'GBP',
            'symbol' => 'CHZ'
        ];

        $headers = [
          'Accepts: application/json',
          'X-CMC_PRO_API_KEY: '.config('app.cmckey')
        ];

        $qs = http_build_query($parameters);
        $request = "{$url}?{$qs}";

        $curl = curl_init();
        curl_setopt_array($curl, array(
          CURLOPT_URL => $request,
          CURLOPT_HTTPHEADER => $headers,
          CURLOPT_RETURNTRANSFER => 1
        ));

        $response = curl_exec($curl);

        if(curl_error($curl)) {
          throw new PriceOracleFailureException(curl_error($curl));
        }

        // @todo - error checking is buggy

        $data = json_decode($response);
        curl_close($curl);
        // temp
        $var1 = (string) "CHZ";

        if(! $data->data?->$var1?->quote?->GBP?->price > 0) {
          throw new PriceOracleFailureException('Invalid response in JSON data');
        }

        return $data->data->$var1->quote->GBP->price;

    }
}
