<?php

namespace Database\Seeders;

use App\Models\Quizstats;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class QuizstatsSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        Quizstats::factory(10)->create();
    }
}
