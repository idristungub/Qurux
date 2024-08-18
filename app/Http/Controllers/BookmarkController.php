<?php

namespace App\Http\Controllers;

use App\library\FetchData;
use App\Models\AchievedUser;
use App\Models\Achievement;
use App\Models\Quizstats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class BookmarkController extends Controller
{


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookmarks = Quizstats::where('bookmarked',true)->where('user_id', Auth::user()->id)->take(10)->get();

        return response()->json($bookmarks);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function storeChapterEasy($chapter, $chapterName, $verse)
    {
        // saving the verse number, difficulty, chapter number and change bookmarked to true

        $user = Auth::user();


        Quizstats::updateOrCreate([
            'user_id' => $user->id,
            'chapter_title' =>  $chapterName,
            'chapter_number' => $chapter,
            'verse_number' => $verse,
            'difficulty' => 'easy',
            'bookmarked' => true,
            'recent' => false
        ]);

        return response()->json(['message' => "You have bookmarked Surah $chapter, verse $verse" ]);

    }


    public function storeJuzEasy($juz, $chapter, $verse)
    {
        $user = Auth::user();

        Quizstats::updateOrCreate([
            'user_id' => $user->id,
            'chapter_number' => $chapter,
            'verse_number' => $verse,
            'juz_number' => $juz,
            'difficulty' => 'easy',
            'bookmarked' => true,
            'recent' => false
        ]);



        return response()->json(['message' => "You have bookmarked Juz $juz, Surah $chapter and verse $verse" ]);
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy($chapter, $verse)
    {
        Quizstats::where('chapter_number', $chapter)
            ->where('verse_number', $verse)
            ->where('user_id', Auth::user()->id)
            ->where('bookmarked', true)
            ->delete();

        return response()->json(['message' => "You have unbookmarked Surah $chapter, verse $verse" ]);



    }
}
