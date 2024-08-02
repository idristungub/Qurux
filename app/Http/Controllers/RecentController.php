<?php

namespace App\Http\Controllers;

use App\Models\Quizstats;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class RecentController extends Controller
{
    public function index() {
        $recent = Quizstats::where('recent',true)->where('user_id', Auth::user()->id)->latest()->get();
        return response()->json($recent);
    }

    public function storeChapterEasy( $chapter,$chapterName, $verse)
    {
        $user = Auth::user();


        Quizstats::updateOrCreate([
            'user_id' => $user->id,
            'chapter_title' =>  $chapterName,
            'chapter_number' => $chapter,
            'verse_number' => $verse,
            'difficulty' => 'easy',
            'bookmarked' => false,
            'recent' => true
        ]);

    }

    public function storeJuzEasy($juz, $chapter, $verse)
    {

        $user = Auth::user();


        Quizstats::updateOrCreate([
            'user_id' => $user->id,
            'chapter_number' => $chapter,
            'verse_number' => $verse,
            'difficulty' => 'easy',
            'juz_number' => $juz,
            'bookmarked' => false,
            'recent' => true
        ]);


    }

    public function destroy($chapter, $verse){
        Quizstats::where('chapter_number', $chapter)
            ->where('verse_number', $verse)
            ->where('user_id', Auth::user()->id)
            ->where('recent', true)
            ->delete();
    }
}
