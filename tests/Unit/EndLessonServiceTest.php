<?php

namespace Tests\Unit;

use Tests\TestCase;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Lesson;
use App\Models\Period;
use App\Enums\EnumLessonStatus;
use App\Services\Lesson\EndLessonService;


class EndLessonServiceTest extends TestCase
{

	use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testLessonWithPendingPeriods()
    {
    	$lesson = $this->createLesson(EnumLessonStatus::PENDING);
    	$this->createPeriod($lesson, EnumLessonStatus::PENDING);
    	$endLessonService = new EndLessonService();
    	$mustEnd = $endLessonService->mustEndLesson($lesson);
        $this->assertNotTrue($mustEnd);
    }

    public function testLessonWithNoPeriods()
    {
        $lesson = $this->createLesson(EnumLessonStatus::PENDING);
        $endLessonService = new EndLessonService();
        $mustEnd = $endLessonService->mustEndLesson($lesson);
        $this->assertTrue($mustEnd);

        $endResult = $endLessonService->endLesson($lesson);
        $this->assertTrue($endResult);

        $this->assertEquals(EnumLessonStatus::CANCELED, $lesson->status);
    }


    public function testLessonWithCanceledPeriods()
    {
    	$lesson = $this->createLesson(EnumLessonStatus::IN_PROGRESS);
    	$this->createPeriod($lesson, EnumLessonStatus::CANCELED);
    	$endLessonService = new EndLessonService();
    	$mustEnd = $endLessonService->mustEndLesson($lesson);
        $this->assertTrue($mustEnd);
        $endResult = $endLessonService->endLesson($lesson);
        $this->assertTrue($endResult);
        $this->assertEquals(EnumLessonStatus::CANCELED, $lesson->status);
    }

	
    public function testLessonWithInprogressPeriods()
    {
    	$lesson = $this->createLesson(EnumLessonStatus::IN_PROGRESS);
    	$this->createPeriod($lesson, EnumLessonStatus::IN_PROGRESS);
    	$endLessonService = new EndLessonService();
    	$mustEnd = $endLessonService->mustEndLesson($lesson);
    	$this->assertNotTrue($mustEnd);
    }


    public function testLessonWithFinishedPeriods()
    {
    	$lesson = $this->createLesson(EnumLessonStatus::IN_PROGRESS);
    	$this->createPeriod($lesson, EnumLessonStatus::FINISHED);
    	$endLessonService = new EndLessonService();
    	$mustEnd = $endLessonService->mustEndLesson($lesson);
        $this->assertTrue($mustEnd);
        $endResult = $endLessonService->endLesson($lesson);
        $this->assertTrue($endResult);
        $this->assertEquals(EnumLessonStatus::FINISHED, $lesson->status);
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
