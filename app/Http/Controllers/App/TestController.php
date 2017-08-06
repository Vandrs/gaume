<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\User;
use App\Services\Lesson\CreateLessonEvaluationService;
use App\Services\User\CalculateUserEvaluationService;

class TestController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	//$lesson = Lesson::find(3);
    	//$service = new CreateLEssonEvaluationService();
    	//$service->create($lesson);
        $user = User::findOrFail(33);

        $service = new CalculateUserEvaluationService();
        $service->calculate($user);

        return 'DONE!!';
    }
}
