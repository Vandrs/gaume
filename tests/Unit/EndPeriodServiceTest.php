<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Lesson;
use App\Models\Period;
use App\Enums\EnumLessonStatus;
use App\Services\Lesson\EndPeriodService;
use Config;
use Carbon\Carbon;
use Mockery;

class EndPeriodServiceTest extends TestCase
{
	use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCancelPeriod()
    {
    	$lesson = $this->createLesson(EnumLessonStatus::IN_PROGRESS);
    	$inProgressPeriod = $this->createPeriod($lesson, EnumLessonStatus::IN_PROGRESS);

    	$endPeriodService = new EndPeriodService();
    	$canCancel = $endPeriodService->canCancelPeriod($inProgressPeriod);
    	$this->assertNotTrue($canCancel);

    	$pendingPeriodInTime = $this->createPeriod($lesson, EnumLessonStatus::PENDING);
    	$canCancel = $endPeriodService->canCancelPeriod($pendingPeriodInTime);
    	$this->assertNotTrue($canCancel);


		

    	$pendingPerioOutOfTime = Mockery::mock(Period::class);
		$this->app->instance(Period::class, $pendingPerioOutOfTime);

		$minutes = Config::get('lesson.confirm_time');

		$pendingPerioOutOfTime->shouldReceive('getAttribute')
							  ->andReturn(
							  		EnumLessonStatus::PENDING, 
							  		Carbon::now()
							  			  ->subMinutes(($minutes+2))
							  	);
		
		$canCancel = $endPeriodService->canCancelPeriod($pendingPerioOutOfTime);
		$this->assertTrue($canCancel);
    }

    public function testFinishPeriod()
    {
    	$lesson = $this->createLesson(EnumLessonStatus::IN_PROGRESS);
    	$pendingPeriod = $this->createPeriod($lesson, EnumLessonStatus::PENDING);

    	$endPeriodService = new EndPeriodService();
    	$canFinish = $endPeriodService->canFinishPeriod($pendingPeriod);
    	$this->assertNotTrue($canFinish);


    	$inTimePeriod = $this->createPeriod($lesson, EnumLessonStatus::IN_PROGRESS);
    	$canCancel = $endPeriodService->canFinishPeriod($inTimePeriod);
    	$this->assertNotTrue($canCancel);
		
    	$outOfTimePeriod = Mockery::mock(Period::class);
		$this->app->instance(Period::class, $outOfTimePeriod);

		$minutes = Config::get('lesson.confirm_time');
		$minutes += 2;
		$hour = Config::get('lesson.lesson_time');

		$outOfTimePeriod->shouldReceive('getAttribute')
							  ->andReturn(
							  		EnumLessonStatus::IN_PROGRESS, 
							  		Carbon::now()
							  			  ->subHours($hour)
							  			  ->subMinutes($minutes),
							  		Config::get('lesson.lesson_time')
							  	);
		
		$canCancel = $endPeriodService->canFinishPeriod($outOfTimePeriod);
		$this->assertTrue($canCancel);
    }



    private function createLesson($status)
    {
        return factory(Lesson::class,1)->create([
                                            'teacher_id' => $this->teacher->id,
                                            'student_id' => $this->student->id,
                                            'status'     => $status
                                        ])
                                       ->first();
    }

    private function createPeriod(Lesson $lesson, $status = null)
    {

        return factory(Period::class,1)->create([
                                            'lesson_id' => $lesson->id,
                                            'status'    => $status ? $status : EnumLessonStatus::IN_PROGRESS
                                        ])
                                        ->first();
    }
}
