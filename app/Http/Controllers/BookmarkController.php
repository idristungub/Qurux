<?php

namespace App\Http\Controllers;

use App\library\FetchData;
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

        return Inertia::render('QuranQuestHome', ['bookmarks' => $bookmarks]);
    }


    /**
     * Store a newly created resource in storage.
     */
    public function store($chapter, $verse, $juz)
    {
        // saving the verse number, difficulty, chapter number and change bookmarked to true

        $user = Auth::user();
        $chapterData = $this->fetchData->fetchChapter();


        $bookmarks = Quizstats::updateOrCreate([
            'user_id' => $user->id,
            'chapter_title' =>  $chapterData['chapter_title'],
            'chapter_number' => $chapter,
            'verse_number' => $verse,
            'juz_number' => $juz,
            'difficulty' => 'Easy',
            'bookmarked' => 'true'
        ]);

        // count number of bookmarks for user
        $bookmarksLength = Quizstats::where('user_id', $user->id)->where('bookmarked', true)->count();

        // find the length of the bookmarked rows if 10 then achieved_points
        if($bookmarks) {
            Achievement::where('achievement_title', 'Bookmark 10 Surahs/Juz')->increment('achieved_points', 1);
            if($bookmarksLength == 10){
                $achievement = Achievement::where('achievement_title', 'Bookmark 10 Surahs/Juz');
                $user->achievements()->attach($achievement->id, ['completed' => true]);

                return response()->json(['status' => 'success', 'message' => 'Completed bookmarking 10 quizzes achievement!']);
            }

            return Inertia::render('QuranQuestHome', ['bookmarks' => $bookmarks]);
        }

    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {
        $bookmarks = Quizstats::find($id);
        if($bookmarks && $bookmarks->bookmarked == true){
            $bookmarks->delete();
        }
    }
}
