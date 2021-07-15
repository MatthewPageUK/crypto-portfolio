<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserSeeder extends Seeder
{
    /**
     * Insert some default data.
     *
     * @return void
     */
    public function run()
    {
        DB::table('users')->insert([
            'name' => 'Demo user',
            'email' => 'demo@example.com',
            'password' => Hash::make('demo'),
            'created_at' => Carbon::now()->format('Y-m-d H:i:s'), 
            'updated_at' => Carbon::now()->format('Y-m-d H:i:s'),
        ]);
    }
}
