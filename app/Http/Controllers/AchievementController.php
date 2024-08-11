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
        // display 2 achievements from the database for all users
        $achievementOneTitle = Achievement::where('id', 1)->pluck('achievement_title');
        $achievementTwoTitle = Achievement::where('id', 2)->pluck('achievement_title');



        return Inertia::render('Achievements', [
            'achievementOneTitle' => $achievementOneTitle,
            'achievementTwoTitle' => $achievementTwoTitle,

            ]);
    }


    public function getTop3()
    {
        // get top 3 if user is found then request true
        $user = Auth::user();
        $userByPoints = User::where('user_id', $user->id)->orderBy('points', 'desc')->take(3)->get();

        if(!$userByPoints->isEmpty()) {

            $getTop3Achievement = Achievement::where('id', '=', 3)->firstOrFail();
            $achievedOne = AchievedUser::updateOrCreate([
                'completed' => true,
                'user_id' => $user->id,
                'achievements_id' => $getTop3Achievement
            ]);

            return response($achievedOne)->json(['message' => 'achievedOne']);
        }

    }


    public function getBookmarks()
    {
        $user = Auth::user();

        $bookmarksLength = Quizstats::where('user_id', $user->id)->where('bookmarked', true)->count();

        // find the length of the bookmarked rows if 10 then achieved_points
        if($bookmarksLength == 10) {
            $bookmarkAchievement = Achievement::where('id', '=', 4)->firstOrFail();
            $achievedTwo = AchievedUser::updateOrCreate([
                'completed' => true,
                'users_id' => $user->id,
                'achievements_id' => $bookmarkAchievement
            ]);

            return response($achievedTwo)->json(['message' => 'achievedTwo']);

        }

    }




}
