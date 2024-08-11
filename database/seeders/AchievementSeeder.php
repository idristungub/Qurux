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
            'achievement_title' => 'Reach top 3 in the leaderboard',
        ]);


        Achievement::create([
            'achievement_title' => 'Bookmark 10 Surahs/Juz ',
        ]);




    }
}
