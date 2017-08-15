<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\User;
use App\Models\LessonEvaluation;
use App\Services\Lesson\CreateLessonEvaluationService;
use App\Services\User\CalculateUserEvaluationService;
use App\Enums\EnumRole;

class TestController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lessonEvaluation = LessonEvaluation::find(6);

        $view = $lessonEvaluation->type == EnumRole::STUDENT ? 'evaluate_lesson_teacher_mail' : 'evaluate_lesson_student_mail';

        $data = [
            'student' =>  $lessonEvaluation->lesson->student->name,
            'teacher' => $lessonEvaluation->lesson->teacher->name,
            'date' => $lessonEvaluation->lesson->created_at->format('d/m/Y'),
            'game' => $lessonEvaluation->lesson->game->name,
            'link' => route('lessons.show', ['id' => $lessonEvaluation->lesson->id])
        ];
        return view('email.'.$view,$data);

//        return "Done!";
    }
}
