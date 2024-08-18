<?php

namespace App\Http\Controllers;

use App\library\FetchData;
use App\Models\AchievedUser;
use App\Models\Achievement;
use App\Models\Quizstats;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AchievementController extends Controller

{

    public function create() {
        $user = Auth::user();
        // display 2 achievements from the database for all users
        $achievementOneTitle = Achievement::where('id', 1)->pluck('achievement_title');
        $achievementTwoTitle = Achievement::where('id', 2)->pluck('achievement_title');
        $achievedOne = AchievedUser::where('users_id', $user->id)->where('achievements_id', 1)->get();
        $achievedTwo = AchievedUser::where('users_id', $user->id)->where('achievements_id', 2)->get();



        return Inertia::render('Achievements', [
            'achievementOneTitle' => $achievementOneTitle,
            'achievementTwoTitle' => $achievementTwoTitle,
            'achievedOne' => $achievedOne,
            'achievedTwo' => $achievedTwo,

            ]);
    }


    public function getTop3()
    {
        // get top 3 if user is found then request true
        $user = Auth::user();

        $getTop3Achievement = Achievement::where('id', '=', 1)->firstOrFail();
        AchievedUser::updateOrCreate([
            'completed' => true,
            'users_id' => $user->id,
            'achievements_id' => $getTop3Achievement->id
        ]);

    }


    public function getBookmarks()
    {
        $user = Auth::user();

        $bookmarked = Quizstats::where('user_id', $user->id)->where('bookmarked', '=', 1)->get();
        $bookmarkAchievement = Achievement::where('id', '=', 2)->firstOrFail();
        if($bookmarked->count() >= 10){
            AchievedUser::updateOrCreate([
                'completed' => true,
                'users_id' => $user->id,
                'achievements_id' => $bookmarkAchievement->id
            ]);
        }


    }

}
