<?php

namespace App\Http\Controllers;

use App\library\FetchData;
use App\Models\Achievement;
use App\Models\Quizstats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class JuzEasyQuizController extends Controller
{

    public function start($juz, $chapter, $verse)
    {
        $user = Auth::user();

        return Inertia::render('JuzEasyQuiz', [
                'juzId' => $juz,
                'chapterId' => $chapter,
                'health_data' => $user->health_status,
                'points_data' => $user->points,
                'verseId' => $verse,
            ]);

    }

    public function next($juz, $chapter, $verse) {


    }

    public function skip($juz, $chapter, $verse) {


    }







}


