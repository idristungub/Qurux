<?php

namespace App\Http\Controllers;

use App\library\FetchData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class AdvanceQuizController extends Controller
{
    protected $fetchData;

    public function __construct(FetchData $fetchData) {
        $this->fetchData = $fetchData;
    }

    public function next($chapter) {
        // generate 4 verses from a random part of a chapter
        $verseData = $this->fetchData->fetchVerses($chapter);
        $total_verses = count($verseData);
        $randomIndex = rand(0, $total_verses - 1);

        for($i = $randomIndex; $i < $total_verses - 1; $i+= 3) {
            return Inertia::render('AdvanceQuiz', [
                'random_verse' => $verseData[$i],
                'chapter' => $chapter,
                'audio' => $verseData['audio'],
            ]);

        }

    }

    public function check(Request $request, $chapter) {
        // checks the order of the verses 1-4


        // request first verse number and last verse number










        $health = Auth::user()->health_status - 1;
        $health->save();
        if ($health == 0) {
            Auth::user()->update(['health_status' => 3]);
            return redirect()->route('quran-quest.home')->with('status', 'Good try better luck next time!');
        }
    }



}
