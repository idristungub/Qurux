<?php

namespace App\Http\Controllers;

use App\library\FetchData;
use App\Models\AchievedUser;
use App\Models\Achievement;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AchievementController extends Controller

{
    protected $fetchData;

    public function __construct(FetchData $fetchData) {
        $this->fetchData = $fetchData;
    }


    public function create(Achievement $achievement) {
        // display 3 achievements from the database for all users

        return Inertia::render('Achievements', [
            'title' => $achievement->achievement_title
            ]);
    }

    public function store(Achievement $achievement)
    {
        $user = Auth::user();
        $idOneCount = 0;

        // achievement one
        if($user->health_points == 3) {
            $idOneCount+= 1;
            if($idOneCount == 3) {
                AchievedUser::create([
                    'completed' => true,
                    'user_id' => $user->id,
                    'achievement_id' => $achievement->where('id', '=', '1')->first()->id
                ]);
            }

        }

        return Inertia::render('Achievement', [
            'countOne' => $idOneCount
        ]);
    }

}
