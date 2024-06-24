<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class LeaderboardController extends Controller
{
    public function create() {
        // get current user 'username' and 'points'
        $currentUserName = Auth::user()->username;
        $currentUserPoints = Auth::user()->points;
        $leaderboardUsers = User::orderBy('points', 'desc')
            ->take(50)
            ->get(['username', 'points']);

        return Inertia::render('Leaderboard', ['leaderboardUsers' => $leaderboardUsers,
            'currentUserName' => $currentUserName,
            'currentUserPoints' => $currentUserPoints]);
    }
}
