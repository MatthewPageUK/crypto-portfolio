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
        $coins = array(
            ['name' => 'Bitcoin', 'symbol' => 'BTC'],
            ['name' => 'Litecoin', 'symbol' => 'LTC'],
            ['name' => 'Ethereum', 'symbol' => 'ETH'],
            ['name' => 'Dogecoin', 'symbol' => 'DOGE'],
            ['name' => 'Matic', 'symbol' => 'MAT'],
            ['name' => 'Curve', 'symbol' => 'CRV'],
            ['name' => 'Ethereum Classic', 'symbol' => 'ETC'],
            ['name' => 'VChain', 'symbol' => 'VET'],
            ['name' => 'Forth Gov.', 'symbol' => 'FORTH'],
            ['name' => 'NuCypher', 'symbol' => 'NU'],
            ['name' => 'Graph', 'symbol' => 'GRT'],
            ['name' => 'Chainlink', 'symbol' => 'LINK'],
        );

        $id = 1;
        foreach($coins as $coin)
        {
            DB::table('crypto_tokens')->insert(['id' => $id, 'name' => $coin['name'], 'symbol' => $coin['symbol'], 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]);
            $id++;
        }
    }
}
