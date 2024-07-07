<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AchievementController extends Controller

{

    public function create(Achievement $achievement) {
        // display 3 achievements from the database for all users

        return Inertia::render('Achievements', ['title' => $achievement->achievement_title,
            'achieved' => $achievement->achieved_points,
            'quantity' => $achievement->quantity,
            'audio_checked' => $achievement->audio_checked,
            'completed' => $achievement->completed]);
    }

    public function store(Request $request) {
        // check if user pressed audio

        // check if reached top 3 of leaderboard


    }


}
