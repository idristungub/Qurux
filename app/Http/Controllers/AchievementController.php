<?php

namespace App\Http\Controllers;

use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AchievementController extends Controller

{

    public function create() {
        // display 3 achievements from the database for all users
        $user = Auth::user()->achievements();

        return Inertia::render('Achievements', ['title' => $user->achievement_title,
            'achieved' => $user->achieved_points,
            'quantity' => $user->quantity,
            'audio_checked' => $user->audio_checked,
            'completed' => $user->completed]);
    }




}
