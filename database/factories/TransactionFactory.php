<?php

namespace Database\Factories;

use App\Models\Token;
use App\Models\Transaction;
use Illuminate\Database\Eloquent\Factories\Factory;

class TransactionFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = Transaction::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'token_id' => Token::factory(),
            'quantity' => rand(1, 90000) / 1000,
            'price' => rand(1, 24500000) / 1000,
            'type' => (rand(0,1)==1) ? Transaction::BUY : Transaction::SELL,
            'time' => $this->faker->dateTimeBetween(),
        ];
    }
}
