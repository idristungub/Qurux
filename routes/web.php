<?php

use App\Http\Controllers\AchievementController;
use App\Http\Controllers\AdvanceQuizController;
use App\Http\Controllers\EasyQuizController;
use App\Http\Controllers\IslamicProfileController;
use App\Http\Controllers\JuzAdvanceQuizController;
use App\Http\Controllers\JuzEasyQuizController;
use App\Http\Controllers\LeaderboardController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\QuizController;
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
    Route::get('/quran-quest')->name('quran-quest.home');


    // put this bookmarks in a resource route
    Route::resource('bookmarks', 'BookmarkController');



    Route::get('/achievements', [AchievementController::class, 'create'])->name('achievements.create');

    // routes for getting the next verse for a specific surah
    Route::get('/quiz/easy/{chapter}/{verse}', [EasyQuizController::class, 'start'])->name('easyQuiz.start');


    // routes for getting the next verse of chapter
    Route::get('/quiz/easy/{chapter}/{verse}', [EasyQuizController::class, 'next'])->name('easyQuiz.next');
    Route::get('/quiz/advance/{chapter}', [AdvanceQuizController::class, 'next'])->name('advanceQuiz.next');

    // routes for getting the next verse for a specific juz
    Route::get('/quiz/easy/{juz}/{chapter}/{verse}', [JuzEasyQuizController::class, 'next'])->name('juzEasyQuiz.next');
    Route::get('/quiz/advance/{juz}/{chapter}', [JuzAdvanceQuizController::class, 'next'])->name('juzAdvanceQuiz.next');

    // bookmark a surah/juz
    Route::get('/quiz/easy/{juz}/{chapter}/{verse}', [EasyQuizController::class, 'bookmark'])->name('EasyQuiz.bookmark');
    Route::get('/quiz/advance/{chapter}', [AdvanceQuizController::class, 'bookmark'])->name('AdvanceQuiz.bookmark');

    // skip route for surah
    Route::get('/quiz/easy/{chapter}/{verse}', [EasyQuizController::class, 'skip'])->name('easyQuiz.skip');
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
