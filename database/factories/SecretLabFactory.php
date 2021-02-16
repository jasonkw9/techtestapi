<?php

namespace Database\Factories;

use App\Models\SecretLab;
use Illuminate\Database\Eloquent\Factories\Factory;
use Faker\Generator as Faker;

class SecretLabFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SecretLab::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'key' => $this->faker->sentence,
            'value' => $this->faker->paragraph,
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
