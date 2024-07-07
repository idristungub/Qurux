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
use Illuminate\Foundation\Application;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', function () {
    return Inertia::render('Welcome', [
        'canLogin' => Route::has('login'),
        'canRegister' => Route::has('register'),
        'laravelVersion' => Application::VERSION,
        'phpVersion' => PHP_VERSION,
    ]);
});

Route::get('/dashboard', function () {
    return Inertia::render('Dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// home page/landing page
Route::get('/home', function () {
    return Inertia::render('Home');
} )->name('home');


// after login and selecting quran quest
// add middleware to check user is on
// list the surahs and juz of quran here

Route::middleware('auth')->group(function () {
    Route::get('/quran-quest' ,function() {
        return Inertia::render('QuranQuestHome');
    })->name('quran-quest.home');




    Route::get('/achievements', [AchievementController::class, 'create'])->name('achievements.create');

    // routes for getting the next verse for a specific surah
    Route::get('/quiz/easy/{chapter}/{verse}', [EasyQuizController::class, 'start'])->name('easyQuiz.start');
    Route::get('/quiz/advance/{chapter}', [AdvanceQuizController::class, 'start'])->name('advanceQuiz.start');

    // routes for getting the next verse of chapter
    Route::get('/quiz/easy/{chapter}/{verse}', [EasyQuizController::class, 'next'])->name('easyQuiz.next');
    Route::get('/quiz/advance/{chapter}', [AdvanceQuizController::class, 'next'])->name('advanceQuiz.next');

    // routes for getting the next verse for a specific juz
    Route::get('/quiz/easy/{juz}/{chapter}/{verse}', [JuzEasyQuizController::class, 'next'])->name('juzEasyQuiz.next');
    Route::get('/quiz/advance/{juz}/{chapter}', [JuzAdvanceQuizController::class, 'next'])->name('juzAdvanceQuiz.next');

    // bookmark a surah/juz
    Route::get('/quiz/easy/{juz}/{chapter}/{verse}', [EasyQuizController::class, 'bookmark'])->name('EasyQuiz.bookmark');
    Route::get('/quiz/advance/{juz}/{chapter}', [AdvanceQuizController::class, 'bookmark'])->name('AdvanceQuiz.bookmark');

    // skip route
    Route::get('/quiz/easy/{juz}/{chapter}/{verse}', [EasyQuizController::class, 'skip'])->name('easyQuiz.skip');
    Route::get('/quiz/advance/{juz}/{chapter}/{verse}', [JuzAdvanceQuizController::class, 'skip'])->name('juzAdvanceQuiz.skip');

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
