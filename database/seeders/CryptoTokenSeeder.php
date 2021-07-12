<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class CryptoTokenSeeder extends Seeder
{
    /**
     * Insert some default data.
     *
     * @return void
     */
    public function run()
    {
        $tokens = array(
            ['name' => 'Bitcoin', 'symbol' => 'BTC'],
            ['name' => 'Ethereum', 'symbol' => 'ETH'],
            ['name' => 'Binance Coin', 'symbol' => 'BNB'],
            ['name' => 'Cardano', 'symbol' => 'ADA'],
            ['name' => 'Litecoin', 'symbol' => 'LTC'],
            ['name' => 'Dogecoin', 'symbol' => 'DOGE'],
            ['name' => 'Curve', 'symbol' => 'CRV'],
            ['name' => 'Ethereum Classic', 'symbol' => 'ETC'],
            ['name' => 'VeChain', 'symbol' => 'VET'],
            ['name' => 'NuCypher', 'symbol' => 'NU'],
            ['name' => 'Graph', 'symbol' => 'GRT'],
            ['name' => 'Chainlink', 'symbol' => 'LINK'],
        );

        foreach($tokens as $token)
        {
            DB::table('crypto_tokens')->insert([
                'name' => $token['name'], 
                'symbol' => $token['symbol'], 
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }
    }
}
