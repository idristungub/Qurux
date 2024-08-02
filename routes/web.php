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
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;




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

    Route::post('recent/easy/{chapter}/{chapterName}/{verse}', [RecentController::class, 'storeChapterEasy'])->name('recent.storeChapterEasy');
    Route::post('recent/easy/juz/{juz}/{chapter}/{verse}', [RecentController::class, 'storeJuzEasy'])->name('recent.storeJuzEasy');

    Route::delete('recent/delete/{chapter}/{verse}', [RecentController::class, 'destroy'])->name('recent.destroy');

    //routes for checking and skipping and storing easyquiz
    Route::post('/checkPoints', [EasyQuizController::class, 'checkPoints'])->name('quiz.checkPoints');
    Route::post('/checkHealth', [EasyQuizController::class, 'checkHealth'])->name('quiz.checkHealth');
    Route::get('/resetHealth', [EasyQuizController::class, 'resetHealth'])->name('quiz.resetHealth');



    Route::get('/achievements', [AchievementController::class, 'create'])->name('achievements.create');


    // routes for getting the next verse for a specific surah
    Route::get('/quiz/easy/{chapter}/{verse}', [EasyQuizController::class, 'start'])->name('easyQuiz.start');

    // routes for getting the next verse for a specific juz
    Route::get('/quiz/easy/juz/{juz}/{chapter}/{verse}', [JuzEasyQuizController::class, 'start'])->name('juzEasyQuiz.start');








    Route::get('/quiz/advance/{juz}/{chapter}', [JuzAdvanceQuizController::class, 'next'])->name('juzAdvanceQuiz.next');

    // bookmark a surah/juz
    Route::get('/quiz/easy/{juz}/{chapter}/{verse}', [EasyQuizController::class, 'bookmark'])->name('easyQuiz.bookmark');
    Route::get('/quiz/advance/{chapter}', [AdvanceQuizController::class, 'bookmark'])->name('AdvanceQuiz.bookmark');

    // skip route for surah

    Route::get('/quiz/advance/{chapter}', [AdvanceQuizController::class, 'skip'])->name('advanceQuiz.skip');

    // skip route for juz
    Route::get('/quiz/easy/{juz}/{chapter}/{verse}', [JuzEasyQuizController::class, 'skip'])->name('JuzEasyQuiz.skip');
    Route::get('/quiz/advance/{juz}/{chapter}', [JuzAdvanceQuizController::class, 'skip'])->name('juzAdvanceQuiz.skip');

    // model for achievements for points to be stored for the achievements specified
    // itll also check the health_status of user
    // used when the "check" button is pressed on the quiz
    Route::post('/quiz/easy/{chapter}/{verse}',[EasyQuizController::class, 'check'])->name('easyQuiz.check');
    Route::post('/quiz/advance/{chapter}',[AdvanceQuizController::class, 'check'])->name('advanceQuiz.check');

    // leaderboard carries the users name and points
    Route::get('/leaderboard', [LeaderboardController::class, 'create'])->name('leaderboard.create');


});

//// get islamic information from user after registering
//Route::get('/islamic-information', [IslamicProfileController::class,'create'])->name('create-profile');
//Route::post('/islamic-information', [IslamicProfileController::class,'store'])->name('islamic-information.create');


Route::middleware('auth')->group(function () {
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
});

require __DIR__.'/auth.php';
