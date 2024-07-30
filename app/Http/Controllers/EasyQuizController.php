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



    public function check()
    {
        if(Auth::check()){
            $user = Auth::user();
            $user->points += 1;
            $user->save();
        }


    }


    public function skip($chapter, $verse) {

    }







}
