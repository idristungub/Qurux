<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\AchievedUser>
 */
class AchievedUserFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'users_id' => $this->faker->numberBetween(1,10),
            'completed' => $this->faker->numberBetween(0,1),
            'achievements_id' => $this->faker->numberBetween(1,10)
        ];
    }
}
