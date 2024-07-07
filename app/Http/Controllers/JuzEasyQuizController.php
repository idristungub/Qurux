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
    public function __construct(FetchData $fetchData) {

        $this->fetchData = $fetchData;
    }

    public function start(Request $request, $juz, $chapter, $verse)
    {
        $juzData = $this->fetchData->fetchJuz($chapter);
        $verseData = $this->fetchData->fetchVerses();
        $chapterData = $this->fetchData->fetchChapter();

        if($request->input('juz_id') === $juzData['juzNumber']) {

            return Inertia::render('JuzEasyQuiz', [
                'juz' => $juz,
                'chapter_id' => $chapter,
                'verse_id' => $verse,
                'first_verse' => $verseData[0],
                'first_chapter' => $chapterData]);
        }
    }
    public function next($juz, $chapter, $verse) {
        // check verse_id is = 1 first then increase by 1  and change the verse aswell but same chapter
        $juzData = $this->fetchData->fetchJuz($chapter);
        $verseData = $this->fetchData->fetchVerses($chapter);

        $nextVerse = $verseData['verse_id'] + 1;

        if($verse > $juzData['last_verse']) {
            return redirect()->route('quran-quest.home')->with('status', 'Try again later!');
        }

        return Inertia::render('JuzEasyQuiz', [
            'verse' => $verseData,
            'nextVerse_id' => $nextVerse,
            'chapter_id' => $chapter,
            'juz_id' => $juz]);

    }

    public function skip($juz, $chapter, $verse) {
        $juzData = $this->fetchData->fetchJuz($chapter);
        $verseData = $this->fetchData->fetchVerses($chapter);

        $nextVerse = $verseData['verse_id'] + 1;

        if($verse > $juzData['last_verse']) {
            return redirect()->route('quran-quest.home')->with('status', 'Try again later!');
        }

        $health = Auth::user()->health_status - 1;
        $health->save();
        if ($health == 0) {
            Auth::user()->update(['health_status' => 3]);
            return redirect()->route('quran-quest.home')->with('status', 'Good try better luck next time!');
        }

        return Inertia::render('JuzEasyQuiz', [
            'verse' => $verseData,
            'nextVerse_id' => $nextVerse,
            'chapter_id' => $chapter,
            'juz_id' => $juz]);

    }







}


