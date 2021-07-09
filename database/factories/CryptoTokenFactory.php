<?php

namespace Database\Factories;

use App\Models\CryptoToken;
use Illuminate\Database\Eloquent\Factories\Factory;

class CryptoTokenFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = CryptoToken::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'name' => $this->faker->text(100),
            'tag' => str_replace(' ', '', strtoupper($this->faker->text(10))),
        ];
    }
}
