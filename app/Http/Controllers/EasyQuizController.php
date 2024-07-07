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


    public function start(Request $request, $chapter, $verse) {
        $chapterData = $this->fetchData->fetchChapter();
        $verseData = $this->fetchData->fetchVerses($chapter);
        if($request->input('chapterTitle') == $chapterData['chapter_title']) {

            return Inertia::render('EasyQuiz', [
                'verse' => $verseData[0],
                'chapter_id' => $chapter,
                'verse_id' => $verse]);

        }
    }

    // this is for "continue" button not the "check" button
    public function next($chapter, $verse) {
        // check verse_id is = 1 first then increase by 1  and change the verse aswell but same chapter
        $verseData = $this->fetchData->fetchVerses($chapter);
        $chapterData = $this->fetchData->fetchChapter();

        $totalVerses = $chapterData['verses_count'];

        $nextVerse = $verseData['verse_id'] + 1;

        if($verse > $totalVerses) {
            return redirect()->route('quran-quest.home')->with('status', 'Try again later!');
        }

        return Inertia::render('EasyQuiz', ['verse' => $verseData, 'nextVerse_id' => $nextVerse, 'chapter_id' => $chapter]);

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

    public function bookmark($juz, $chapter, $verse) {
        // saving the verse number, difficulty, chapter number and change bookmarked to true

        $user = Auth::user();
        $chapterData = $this->fetchData->fetchChapter();


        $bookmark = Quizstats::updateOrCreate([
            'user_id' => $user->id,
            'chapter_title' =>  $chapterData['chapter_title'],
            'chapter_number' => $chapter,
            'verse_number' => $verse,
            'juz_number' => $juz,
            'difficulty' => 'easy',
            'bookmarked' => 'true'
        ]);

        // count number of bookmarks for user
        $bookmarksLength = Quizstats::where('user_id', $user->id)->where('bookmarked', true)->count();

        // find the length of the bookmarked rows if 10 then achieved_points
        if($bookmark) {
            Achievement::where('achievement_title', 'Bookmark 10 Surahs/Juz')->increment('achieved_points', 1);
            if($bookmarksLength == 10){
                Achievement::where('achievement_title', 'Bookmark 10 Surahs/Juz')->update(['completed' => true]);
                return response()->json(['status' => 'success', 'message' => 'Completed bookmarking 10 quizzes achievement!']);
            }

            return Inertia::render('QuranQuestHome', ['bookmark' => $bookmark]);
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
        $health->save();
        if ($health == 0) {
            Auth::user()->update(['health_status' => 3]);
            return redirect()->route('quran-quest.home')->with('status', 'Good try better luck next time!');
        }

        return Inertia::render('EasyQuiz', ['verse' => $verseData, 'nextVerse_id' => $nextVerse, 'chapter_id' => $chapter]);

    }





}
