<?php

namespace App\Http\Controllers;

use App\library\FetchData;
use App\Models\Achievement;
use App\Models\Quizstats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;
use function Sodium\increment;

class EasyQuizController extends Controller
{
    protected $fetchData;

    public function __construct(FetchData $fetchData) {
        $this->fetchData = $fetchData;
    }


    public function start($chapter, $verse) {

        return Inertia::render('QuizEasy', [
            'chapterId' => $chapter,
            'verseId' => $verse,
        ]);

    }




    public function check(Request $request, $chapter, $verse)
    {
        // check if verse matches the text verse on page
        $verseData = $this->fetchData->fetchVerses($chapter);


        if($request->input($verse) === $verseData['actual_verse'] ) {
            // add 2 points to user
            $points = Auth::user()->points + 2;
            $points->save();

                // success message for getting it right
            return response()->json(['status' => 'success', 'points' => $points]);

        }


            // health points decrease by 1 if health reaches 0
        $health = Auth::user()->health_status - 1;
        $health->save();
        if ($health == 0) {
            Auth::user()->update(['health_status' => 3]);
            return redirect()->route('quran-quest.home')->with('status', 'Good try better luck next time!');
        }


    }


    public function skip($chapter, $verse) {
        $verseData = $this->fetchData->fetchVerses($chapter);
        $chapterData = $this->fetchData->fetchChapter();

        $totalVerses = $chapterData['verses_count'];

        $nextVerse = $verseData['verse_id'] + 1;

        if($verse > $totalVerses) {
            return redirect()->route('quran-quest.home')->with('status', 'Try again later!');
        }

        $health = Auth::user()->health_status - 1;
        $points = Auth::user()->points - 1;

        $points->save();
        $health->save();
        if ($health == 0) {
            Auth::user()->update(['health_status' => 3]);
            return redirect()->route('quran-quest.home')->with('status', 'Good try better luck next time!');
        }


    }







}
