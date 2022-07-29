<?php

namespace App\Support;

use App\Interfaces\BackupInterface;
use App\Models\Token;
use App\Models\Transaction;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

class Coinmarketcap
{
    public $url = 'https://pro-api.coinmarketcap.com/';

    public function __construct()
    {

    }

    // Nasty hacky throw it in code ...

    public function getPrice()
    {
        $url = 'https://pro-api.coinmarketcap.com/v1/cryptocurrency/quotes/latest';

        $parameters = [
            'convert' => 'GBP',
            'symbol' => 'CHZ'
        ];

        $headers = [
          'Accepts: application/json',
          'X-CMC_PRO_API_KEY: '.config('app.cmckey')
        ];

        $qs = http_build_query($parameters); // query string encode the parameters
        $request = "{$url}?{$qs}"; // create the request URL


        $curl = curl_init(); // Get cURL resource
        // Set cURL options
        curl_setopt_array($curl, array(
          CURLOPT_URL => $request,            // set the request URL
          CURLOPT_HTTPHEADER => $headers,     // set the headers
          CURLOPT_RETURNTRANSFER => 1         // ask for raw response instead of bool
        ));

        $response = curl_exec($curl); // Send the request, save the response

        $data = json_decode($response); // print json decoded response

        if(curl_error($curl)) return curl_error($curl);

        //return $data;

        $var1 = (string) "CHZ";

        return $data->data->$var1->quote->GBP->price;

        curl_close($curl); // Close request

    }


}



?>