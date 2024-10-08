<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AdvanceQuizController;
use App\Http\Controllers\BookmarkController;
use App\Http\Controllers\EasyQuizController;
use App\Http\Controllers\IslamicProfileController;
use App\Http\Controllers\JuzAdvanceQuizController;
use App\Http\Controllers\JuzEasyQuizController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
use App\Http\Controllers\RecentController;
use App\library\FetchData;
use App\Models\Quizstats;
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;


// auto run migrations
Route::get('/run-migration', function () {
    Artisan::call('optimize:clear');
    Artisan::call('migrate:fresh --seed');
});



// home page/landing page
Route::get('/', function () {
    return Inertia::render('Home', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register')
    ]);
} )->name('home');


// after login and selecting quran quest
// add middleware to check user is on
// list the surahs and juz of quran here

Route::middleware('auth')->group(function () {

    Route::get('/quran-quest', function() {
        $user = Auth::user();
        $user->health_status = 3;
        $user->save();
        return Inertia::render('QuranQuestHome');
    })->name('quran-quest.home');


    // put this bookmarks and recent route
    Route::get('bookmarks', [BookmarkController::class, 'index'])->name('bookmarks.index');


    Route::post('bookmarks/easy/{chapter}/{chapterName}/{verse}', [BookmarkController::class, 'storeChapterEasy'])->name('bookmarks.storeChapterEasy');
    Route::post('bookmarks/easy/juz/{juz}/{chapter}/{verse}', [BookmarkController::class, 'storeJuzEasy'])->name('bookmarks.storeJuzEasy');


    Route::delete('bookmarks/delete/{chapter}/{verse}', [BookmarkController::class, 'destroy'])->name('bookmarks.destroy');

    Route::get('recent', [RecentController::class, 'index'])->name('recent.index');

    // route for recent easy and advance
    Route::post('recent/easy/{chapter}/{chapterName}/{verse}', [RecentController::class, 'storeChapterEasy'])->name('recent.storeChapterEasy');
    Route::post('recent/easy/juz/{juz}/{chapter}/{verse}', [RecentController::class, 'storeJuzEasy'])->name('recent.storeJuzEasy');
    Route::post('recent/advance/{chapter}/{chapterName}', [RecentController::class, 'storeChapterAdvance'])->name('recent.storeChapterAdvance');

    Route::delete('recent/delete/{chapter}/{verse}', [RecentController::class, 'destroy'])->name('recent.destroy');



    //routes for checking and skipping and storing easyquiz
    Route::post('/checkPointsEasy', [EasyQuizController::class, 'checkPoints'])->name('quiz.checkPoints');
    Route::post('/checkHealth', [EasyQuizController::class, 'checkHealth'])->name('quiz.checkHealth');
    Route::get('/resetHealth', [EasyQuizController::class, 'resetHealth'])->name('quiz.resetHealth');


    // routes for checking for points and health in advance
    Route::post('/checkPointsAdvance', [AdvanceQuizController::class, 'checkPointsAdvance'])->name('quiz.checkPointsAdvance');


    // routes for achievements
    Route::get('/achievements', [AchievementController::class, 'create'])->name('achievements.create');
    Route::post('/bookmarkAchievement', [AchievementController::class, 'getBookmarks'])->name('achievements.getBookmarks');
    Route::post('/top3Achievement', [AchievementController::class, 'getTop3'])->name('achievements.getTop3');


    // routes for getting the next verse for a specific surah
    Route::get('/quiz/easy/{chapter}/{verse}', [EasyQuizController::class, 'start'])->name('easyQuiz.start');

    // routes for getting the next verse for a specific juz
    Route::get('/quiz/easy/juz/{juz}/{chapter}/{verse}', [JuzEasyQuizController::class, 'start'])->name('juzEasyQuiz.start');

    // routes for getting next 4 verses for specific surah
    Route::get('/quiz/advance/{chapter}',[AdvanceQuizController::class, 'start'])->name('advanceQuiz.start');

    // routes for getting next 4 verses for specific juz
    Route::get('/quiz/advance/{juz}/{chapter}', [JuzAdvanceQuizController::class, 'start'])->name('juzAdvanceQuiz.start');


    // leaderboard carries the users name and points
    Route::get('/leaderboard', [LeaderboardController::class, 'create'])->name('leaderboard.create');


});

require __DIR__.'/auth.php';
