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
        $verseData = $this->fetchData->fetchVerses($chapter);
        $randomVersesList = $this->fetchData->fetchRandomVerses($chapter);
        return Inertia::render('AdvanceQuiz', [
            'verses' => $randomVersesList,
            'chapter' => $chapter,
            'audio' => $verseData['audio'],
        ]);

    }

    public function check(Request $request, $chapter) {
        $randomVersesList = $this->fetchData->fetchRandomVerses($chapter);
        $verseData = $this->fetchData->fetchVerses($chapter);
        // checks the order of the verses 1-4
        $choice1 = $request->choices['1'];
        $choice2 = $request->choices['2'];
        $choice3 = $request->choices['3'];
        $choice4 = $request->choices['4'];

        // get the

        // check the values of these keys are equal to that of the list
        if($choice1 == $randomVersesList[0]
            && $choice2 == $randomVersesList[1]
            && $choice3 == $randomVersesList[2]
            && $choice4 == $randomVersesList[3]) {


        }










        // request first verse number and last verse number










        $health = Auth::user()->health_status - 1;
        $health->save();
        if ($health == 0) {
            Auth::user()->update(['health_status' => 3]);
            return redirect()->route('quran-quest.home')->with('status', 'Good try better luck next time!');
        }
    }



}
