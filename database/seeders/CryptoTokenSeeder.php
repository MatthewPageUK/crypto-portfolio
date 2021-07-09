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
        DB::table('crypto_tokens')->insert(['name' => 'Bitcoin', 'tag' => 'BTC', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]);
        DB::table('crypto_tokens')->insert(['name' => 'Litecoin', 'tag' => 'LTC', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]);
        DB::table('crypto_tokens')->insert(['name' => 'Ethereum', 'tag' => 'ETH', 'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 'updated_at' => Carbon::now()->format('Y-m-d H:i:s')]);
    }
}
