<?php

namespace App\Http\Controllers;

use App\library\FetchData;
use App\Models\Achievement;
use App\Models\Quizstats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdvanceQuizController extends Controller
{

    public function start($chapter) {
        $user = Auth::user();

        return Inertia::render('QuizAdvance', [
            'chapterId' => $chapter,
            'points_data' => $user->points,
            'health_data' => $user->health_status
        ]);

    }


    public function checkPointsAdvance() {
        if(Auth::check()){
            $user = Auth::user();
            $user->points += 5;
            $user->save();
        }
    }



}
