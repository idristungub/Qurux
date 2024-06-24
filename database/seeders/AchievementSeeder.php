<?php

namespace Database\Seeders;

use App\Models\Achievement;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AchievementSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Achievement::create([
            'achievement_title' => 'Completed 3 Surahs with 3 lives',
            'achieved_points' => 0,
            'quantity' => 3,
            'audio_checked' => false,
            'completed' => false
        ]);

        Achievement::create([
            'achievement_title' => 'Completed 5 Surahs without listening to it',
            'achieved_points' => 0,
            'quantity' => 5,
            'audio_checked' => false,
            'completed' => false
        ]);

        Achievement::create([
            'achievement_title' => 'Reach top 3 in the leaderboard',
            'achieved_points' => 0,
            'quantity' => 1,
            'audio_checked' => false,
            'completed' => false
        ]);


        Achievement::create([
            'achievement_title' => 'Bookmark 10 Surahs/Juz ',
            'achieved_points' => 0,
            'quantity' => 10,
            'audio_checked' => false,
            'completed' => false
        ]);




    }
}
