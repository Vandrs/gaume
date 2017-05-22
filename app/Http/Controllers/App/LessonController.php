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
        return view('app.lesson.index');
    }

    public function show(Request $request, $id)
    {
    	return view('app.lesson.show',['lessonId' => $id]);
    }
}
