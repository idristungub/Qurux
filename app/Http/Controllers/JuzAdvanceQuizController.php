<?php

namespace App\Http\Controllers;

use App\library\FetchData;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class JuzAdvanceQuizController extends Controller
{



    public function start() {

            return Inertia::render('JuzAdvanceQuiz', [

            ]);


    }


}
