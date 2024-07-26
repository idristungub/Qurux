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
    public function create(Achievement $achievement) {
        // get current user 'username' and 'points'
        $idCountTwo = 0;
        $currentUserName = Auth::user()->username;
        $currentUserPoints = Auth::user()->points;
        $leaderboardUsers = User::orderBy('points', 'desc')
            ->take(50)
            ->get(['username', 'points']);

        // achievements for id 3
        $currentUserPosition = null;

        foreach ($leaderboardUsers as $index => $user) {
            if($user->username == $currentUserName) {
                $currentUserPosition = $index + 1;
                break;
            }
        }

        if ($currentUserPosition == 3) {
            $idCountTwo += 1;
            if($idCountTwo == 1) {
                AchievedUser::create([
                    'completed' => true,
                    'user_id' => Auth::user()->id,
                    'achievement_id' => 3
                ]);

                return Inertia::render('Achievements', [
                    'countTwo' => $idCountTwo,
                ]);
            }


        }


        return Inertia::render('Leaderboard', [
            'leaderboardUsers' => $leaderboardUsers,
            'currentUserName' => $currentUserName,
            'currentUserPoints' => $currentUserPoints]);
    }

}
