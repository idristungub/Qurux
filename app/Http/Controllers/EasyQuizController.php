<?php

namespace App\Http\Controllers;

use App\library\FetchData;
use App\Models\Achievement;
use App\Models\Quizstats;
use App\Models\User;
use Faker\Core\Number;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use Inertia\Response;
use function Sodium\increment;

class EasyQuizController extends Controller
{

    public function start($chapter, $verse) {
        $user = Auth::user();
        return Inertia::render('QuizEasy', [
            'chapterId' => $chapter,
            'verseId' => $verse,
            'health_data' => $user->health_status,
            'points_data' => $user->points
        ]);

    }



    public function checkPoints(Request $request)
    {
        if(Auth::check()){
            $user = Auth::user();
            $user->points += 1;
            $user->save();
        }
    }

    public function checkHealth()
    {
        if(Auth::check()){
            $user = Auth::user();
            if($user->health_status > 0){
                $user = Auth::user();
                $user->health_status -= 1;
                $user->save();
            }


            return response()->json(['health_status' => $user->health_status]);

        }

        return response()->json(['error' => 'unauthorized']);

    }

    public function resetHealth()
    {
        if(Auth::check()){
            $user = Auth::user();
            if($user->health_status == 0) {
                User::where('health_status', 0)->update(['health_status' => 3]);
                $user->save();
                return to_route('quran-quest.home');


            }
        }

    }









}
