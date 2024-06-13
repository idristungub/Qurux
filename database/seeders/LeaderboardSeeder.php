<?php

namespace Database\Seeders;

use App\Models\Leaderboard;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LeaderboardSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Leaderboard::factory(10)->Create();
    }
}
