<?php

namespace App\Http\Controllers;

use App\library\FetchData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class JuzAdvanceQuizController extends Controller
{
    protected $fetchData;

    public function __construct(FetchData $fetchData) {
        $this->fetchData = $fetchData;
    }
    public function next(Request $request, $juz, $chapter) {
        $verseData = $this->fetchData->fetchVerses($chapter);
        $randomVersesList = $this->fetchData->fetchRandomVerses($chapter);
        if($request->input('juz_id') == $juz) {
            return Inertia::render('JuzAdvanceQuiz', [
                'verses' => $randomVersesList,
                'chapter_id' => $chapter,
                'juz_id' => $juz,
                'audio' => $verseData['audio']
            ]);

        }
    }

    public function check(Request $request, $juz, $chapter) {
        $randomVersesList = $this->fetchData->fetchRandomVerses($chapter);
        // checks the order of the verses 1-4
        $choice1 = $request->choices['1'];
        $choice2 = $request->choices['2'];
        $choice3 = $request->choices['3'];
        $choice4 = $request->choices['4'];
        $chapterId = $request->choices['chapter_guess'];
        $startVerse = $request->choices['start_verse'];
        $endVerse = $request->choices['end_verse'];


        // check the values of these keys are equal to that of the list
        if($choice1 == $randomVersesList[0]
            && $choice2 == $randomVersesList[1]
            && $choice3 == $randomVersesList[2]
            && $choice4 == $randomVersesList[3]) {

            $points = Auth::user()->points + 1;
            $points->save();
            return response()->json(['success' => true, 'you got all verses correct but not numbers of start to end verses and chapter number correct!']);

        } else if($choice1 == $randomVersesList[0]['actual_verse']
            && $choice2 == $randomVersesList[1]['actual_verse']
            && $choice3 == $randomVersesList[2]['actual_verse']
            && $choice4 == $randomVersesList[3]['actual_verse']
            && $startVerse == $randomVersesList[0]['verse_id']
            && $endVerse == $randomVersesList[3]['verse_id']
            && $chapterId == $randomVersesList['chapter_id']) {

            $points = Auth::user()->points + 5;
            $points->save();
            return response()->json(['success' => true, 'you got it all correct!']);

        }

        $health = Auth::user()->health_status - 1;
        $health->save();
        if ($health == 0) {
            Auth::user()->update(['health_status' => 3]);
            return redirect()->route('quran-quest.home')->with('status', 'Good try better luck next time!');
        }
    }

    public function skip($juz, $chapter) {
        $verseData = $this->fetchData->fetchVerses($chapter);
        $randomVersesList = $this->fetchData->fetchRandomVerses($chapter);

        $health = Auth::user()->health_status - 1;
        $health->save();

        if ($health == 0) {
            Auth::user()->update(['health_status' => 3]);
            return redirect()->route('quran-quest.home')->with('status', 'Good try better luck next time!');
        }

        return Inertia::render('JuzAdvanceQuiz', [
            'verses' => $randomVersesList,
            'chapter_id' => $chapter,
            'juz_id' => $juz,
            'audio' => $verseData['audio'],
        ]);
    }



}
