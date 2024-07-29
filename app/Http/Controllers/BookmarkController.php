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
    protected $fetchData;

    public function __construct(FetchData $fetchData) {
        $this->fetchData = $fetchData;
    }


    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $bookmarks = Quizstats::where('bookmarked',true)->where('user_id', Auth::user()->id)->take(7)->get();

        return response()->json($bookmarks);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function storeEasy($chapter, $chapterName, $verse)
    {
        // saving the verse number, difficulty, chapter number and change bookmarked to true

        $user = Auth::user();


        Quizstats::updateOrCreate([
            'user_id' => $user->id,
            'chapter_title' =>  $chapterName,
            'chapter_number' => $chapter,
            'verse_number' => $verse,
            'difficulty' => 'easy',
            'bookmarked' => true
        ]);

        // count number of bookmarks for user
        $bookmarksLength = Quizstats::where('user_id', $user->id)->where('bookmarked', true)->count();

        // find the length of the bookmarked rows if 10 then achieved_points
        if($bookmarksLength == 10) {
            $bookmarkAchievement = Achievement::where('id', '=', 4)->firstOrFail();
            AchievedUser::updateOrCreate([
                'completed' => true,
                'users_id' => $user->id,
                'achievements_id' => $bookmarkAchievement
            ]);
        }

        return response()->json(['message' => "You have bookmarked Surah $chapterName, verse $verse" ]);

    }

    public function storeAdvance($chapter, $chapterName, $verse) {

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
