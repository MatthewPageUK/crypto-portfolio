<?php

namespace Database\Factories;

use App\Models\CryptoToken;
use App\Models\CryptoTransaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class CryptoTransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CryptoTransaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'crypto_token_id' => CryptoToken::factory(),
            'quantity' => rand(1, 90000) / 1000,
            'price' => rand(1, 24500000) / 1000,
            'type' => (rand(0,1)==1)?'buy':'sell',
            'time' => $this->faker->dateTimeBetween(),
        ];
    }
}
