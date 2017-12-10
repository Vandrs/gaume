<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Lesson;
use App\Models\User;
use App\Models\LessonEvaluation;
use App\Services\Billing\CalculateLessonBillingService;
use App\Services\Billing\GetLessonBillingService;
use App\Enums\EnumRole;
use App\Enums\EnumLessonStatus;
use \Pusher;

class TestController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {

        $lesson = Lesson::findOrFail(30);
        $user = $request->user();

        $periods = $lesson->periods->filter(function($period){
            return $period->status == EnumLessonStatus::FINISHED;
        });

        $hours = $periods->sum('hours');
        $game = $lesson->game->name;
        $date = $lesson->created_at->format('d/m/Y');

        $mailData = [
            "teacher" => $lesson->teacher->name,
            "game"    => $game,
            "student" => $lesson->student->name,
            "date"    => $date,
            "hours"   => $hours,
            "link"    => route('lessons.show',['id' => $lesson->id])
        ];
        $view = $user->hasRole(EnumRole::TEACHER) ? 'email.confirmation_email_teacher' : 'email.confirmation_email_student';
        return view($view,$mailData);
    }
}
