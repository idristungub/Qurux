<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Achievement>
 */
class AchievementFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */

    protected static $achievement =  ['Completed 3 surahs with 3 lives', 'Completed 5 surahs without listening to it', 'Reach top 3 in the leaderboard', 'Bookmark 10 surahs/juz'];

    protected static $quantity = [10, 3, 6, 8];

    public function definition(): array
    {

        $users = User::pluck("id");
        return [
            'user_id' => fake()->randomElement($users),
            'achievement_title' => fake()->randomElement(self::$achievement),
            'achieved_points' => fake()->numberBetween(0, self::$quantity),
            'quantity' => fake()->randomElement(self::$quantity),
            'completed' => fake()->boolean,

        ];
    }
}
