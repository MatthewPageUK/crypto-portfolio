<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class TransactionSeeder extends Seeder
{
    /**
     * Insert some default data.
     *
     * @return void
     */
    public function run()
    {
        $this->demoData();
    }

    public function demoData()
    {
        DB::table('transactions')->delete();

        $tokens = array(
            ['name' => 'Fake ADA Coin', 'symbol' => 'FADA'],
            ['name' => 'Fake BTC Coin', 'symbol' => 'FBTC'],
            ['name' => 'Fake SHIBA Coin', 'symbol' => 'FSHIBA'],
        );

        foreach($tokens as $token)
        {
            DB::table('tokens')->insert([
                'name' => $token['name'], 
                'symbol' => $token['symbol'], 
                'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 
                'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
            ]);
        }        

        $fbtc = DB::table('tokens')->where('symbol', 'FBTC')->first();
        $fada = DB::table('tokens')->where('symbol', 'FADA')->first();
        $fshiba = DB::table('tokens')->where('symbol', 'FSHIBA')->first();

        // make transactions
        $toMake = 50;
        $balance = 0;
        $marketType = 'bull';
        $priceFrom = 22000;
        $priceTo = 24000;

        for($x = 0; $x < $toMake; $x++)
        {
            $type = (rand(1, 10)>3) ? 'buy':'sell';

            if($type === 'buy')
            {
                // Buy random quantity
                $quantity = rand(1, 100000) / 1000000;
                $balance += $quantity;
            }
            else
            {
                // Sell 70% of balance
                $quantity = ($balance / 100) * 70;
                $balance -= $quantity;
            }

            // Random price in range
            $price = rand($priceFrom, $priceTo);
            
            DB::table('transactions')->insert([
                'token_id' => $fbtc->id, 
                'quantity' => $quantity, 
                'price' => $price,
                'type' => $type,
                'time' => Carbon::now()->subDays($toMake-$x)->format('Y-m-d H:i:s'), 
                'created_at' => Carbon::now()->subDays($toMake-$x)->format('Y-m-d H:i:s'), 
                'updated_at' => Carbon::now()->subDays($toMake-$x)->format('Y-m-d H:i:s'),
            ]);

            if($marketType === 'bull') 
            {
                // Increase prices by 0.5%
                $priceFrom = $priceFrom + ($priceFrom / 200);
                $priceTo = $priceTo + ($priceTo / 200);
            }
            else
            {
                // Decrease prices by 0.5%
                $priceFrom = $priceFrom - ($priceFrom / 200);
                $priceTo = $priceTo - ($priceTo / 200);
            }

            // Change market type 5% chance
            if( rand(1, 100) < 5 )
            {
                $marketType = ($marketType === 'bull') ? 'bear':'bull';
            }
        }


















        // ADA trans
        // make transactions
        $toMake = 150;
        $balance = 0;
        $marketType = 'bull';
        $priceFrom = 950;
        $priceTo = 1050;

        for($x = 0; $x < $toMake; $x++)
        {
            $type = (rand(1, 10)>3) ? 'buy':'sell';

            if($type === 'buy')
            {
                // Buy random quantity
                $quantity = rand(1, 1000) / 10;
                $balance += $quantity;
            }
            else
            {
                // Sell 70% of balance
                $quantity = ($balance / 100) * 70;
                $balance -= $quantity;
            }

            // Random price in range
            $price = rand($priceFrom, $priceTo) / 100;
            
            DB::table('transactions')->insert([
                'token_id' => $fada->id, 
                'quantity' => $quantity, 
                'price' => $price,
                'type' => $type,
                'time' => Carbon::now()->subDays($toMake-$x)->format('Y-m-d H:i:s'), 
                'created_at' => Carbon::now()->subDays($toMake-$x)->format('Y-m-d H:i:s'), 
                'updated_at' => Carbon::now()->subDays($toMake-$x)->format('Y-m-d H:i:s'),
            ]);

            if($marketType === 'bull') 
            {
                // Increase prices by 0.5%
                $priceFrom = $priceFrom + ($priceFrom / 200);
                $priceTo = $priceTo + ($priceTo / 200);
            }
            else
            {
                // Decrease prices by 0.5%
                $priceFrom = $priceFrom - ($priceFrom / 200);
                $priceTo = $priceTo - ($priceTo / 200);
            }

            // Change market type 5% chance
            if( rand(1, 100) < 5 )
            {
                $marketType = ($marketType === 'bull') ? 'bear':'bull';
            }
        }




    }

}
