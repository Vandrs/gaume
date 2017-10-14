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
use \Pusher;

class TestController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $lessons = GetLessonBillingService::getAll();
        return $lessons->count();
    }
}
