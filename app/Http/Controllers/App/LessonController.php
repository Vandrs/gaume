<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class LessonController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        die('Minhas Aulas');
    }

    public function show(Request $request, $id)
    {
    	return view('app.lesson.show',['lessonId' => $id]);
    }
}
