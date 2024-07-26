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
    protected $fetchData;

    public function __construct(FetchData $fetchData) {
        $this->fetchData = $fetchData;
    }

    public function index($chapter) {
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


        // check the values of these keys are equal to that of the list
        if($choice1 == $randomVersesList[0]
            && $choice2 == $randomVersesList[1]
            && $choice3 == $randomVersesList[2]
            && $choice4 == $randomVersesList[3]) {

            $points = Auth::user()->points + 1;
            $points->save();
            return response()->json(['success' => true, 'you got all verses correct but not numbers of start to end verses correct!']);

        } else if($choice1 == $randomVersesList[0]['actual_verse']
            && $choice2 == $randomVersesList[1]['actual_verse']
            && $choice3 == $randomVersesList[2]['actual_verse']
            && $choice4 == $randomVersesList[3]['actual_verse']
            && $request->input('start_verse') == $randomVersesList[0]['verse_id']
            && $request->input('end_verse') == $randomVersesList[3]['verse_id']) {

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

    public function bookmark(Request $request, $juz, $chapter) {
        $user = Auth::user();
        $chapterData = $this->fetchData->fetchChapter();

        $bookmark = Quizstats::updateOrCreate([
            'user_id' => $user->id,
            'chapter_title' =>  $chapterData['chapter_title'],
            'chapter_number' => $chapter,
            'difficulty' => 'Advance',
            'bookmarked' => 'true'
        ]);

        return Inertia::render('QuranQuestHome', ['bookmark' => $bookmark]);
    }

    public function skip($chapter)
    {
        $verseData = $this->fetchData->fetchVerses($chapter);
        $randomVersesList = $this->fetchData->fetchRandomVerses($chapter);

        $health = Auth::user()->health_status - 1;
        $health->save();

        if ($health == 0) {
            Auth::user()->update(['health_status' => 3]);
            return redirect()->route('quran-quest.home')->with('status', 'Good try better luck next time!');
        }

        return Inertia::render('AdvanceQuiz', [
            'verses' => $randomVersesList,
            'chapter_id' => $chapter,
            'audio' => $verseData['audio'],
        ]);


    }



}
