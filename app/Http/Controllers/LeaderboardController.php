<?php

namespace App\Http\Controllers;

use App\Models\AchievedUser;
use App\Models\Achievement;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LeaderboardController extends Controller
{
    public function create() {
        $leaderboardUsers = User::orderBy('points', 'desc')
            ->skip(3)
            ->take(50)
            ->get(['username', 'points']);

        $topThree = User::orderBy('points', 'desc')->take(3)->get(['username', 'points']);




        return Inertia::render('Leaderboard', [
            'leaderboardUsers' => $leaderboardUsers,
            'topThree' => $topThree]);
    }

}
