<?php

namespace Database\Factories;

use App\Models\SecretLabChange;
use Illuminate\Database\Eloquent\Factories\Factory;

class SecretLabChangeFactory extends Factory
{
    /**
     * The name of the factory's corresponding model.
     *
     * @var string
     */
    protected $model = SecretLabChange::class;

    /**
     * Define the model's default state.
     *
     * @return array
     */
    public function definition()
    {
        return [
            'key' => $this->faker->sentence,
            'old_value' => $this->faker->paragraph,
            'updated_value' => $this->faker->paragraph,
            'original_created_at' => now(),
            'created_at' => now(),
            'updated_at' => now()
        ];
    }
}
