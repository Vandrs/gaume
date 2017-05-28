<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Config;
use App\Models\Lesson;
use App\Models\Period;
use App\Enums\EnumRole;
use App\Enums\EnumLessonStatus;

class ApiConfirmDenyPeriodTest extends TestCase
{

    use DatabaseTransactions;

    public function testUnauthorizedAccess()
    {
        $lesson = $this->createLesson();
        $period = $this->createPeriod($lesson);
        $response = $this->json('PATCH','/api/lessons/'.$lesson->id.'/periods/'.$period->id);
        $response->assertStatus(401);
    }

    public function testNotFound()
    {
        $lesson = $this->createLesson();
        $period = $this->createPeriod($lesson);
        $response = $this->json('PATCH','/api/lessons/'.$lesson->id.'/periods/'.($period->id+1), [], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(404);
    }

    public function testStudentConfirm()
    {
        $lesson = $this->createLesson();
        $period = $this->createPeriod($lesson, EnumLessonStatus::PENDING);
        $response = $this->json('PATCH','/api/lessons/'.$lesson->id.'/periods/'.$period->id, ['confirmed' => true], $this->getHeadersApiToken($this->student));
        $response->assertStatus(401);
    }


    public function testStudentDeny()
    {
        $lesson = $this->createLesson();
        $period = $this->createPeriod($lesson);
        $response = $this->json('PATCH','/api/lessons/'.$lesson->id.'/periods/'.$period->id, ['confirmed' => false], $this->getHeadersApiToken($this->student));
        $response->assertStatus(401);
    }

    
    public function testTeacherConfirmBadRequest()
    {
        $lesson = $this->createLesson();
        $period = $this->createPeriod($lesson, EnumLessonStatus::PENDING);
        $response = $this->json('PATCH', '/api/lessons/'.$lesson->id.'/periods/'.$period->id, ["confirmed" => ""], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(400);
        $response = $this->json('PATCH', '/api/lessons/'.$lesson->id.'/periods/'.$period->id, ["confirmed"], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(400);
        $response = $this->json('PATCH', '/api/lessons/'.$lesson->id.'/periods/'.$period->id, [], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(400);
    }


    
    public function testTeacherConfirm()
    {
        $lesson = $this->createLesson();
        $period = $this->createPeriod($lesson, EnumLessonStatus::PENDING);
        $response = $this->json('PATCH', '/api/lessons/'.$lesson->id.'/periods/'.$period->id, ["confirmed" => true], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(204);
        $this->assertDatabaseHas('lessons',[
            'id'     => $lesson->id,
            'status' => EnumLessonStatus::IN_PROGRESS
        ]);
        $this->assertDatabaseHas('periods',[
            'id'     => $period->id,
            'status' => EnumLessonStatus::IN_PROGRESS
        ]);        
    }

    public function testTeacherDeny()
    {
        $lesson = $this->createLesson();
        $period = $this->createPeriod($lesson, EnumLessonStatus::PENDING);
        $response = $this->json('PATCH', '/api/lessons/'.$lesson->id.'/periods/'.$period->id, ["confirmed" => false], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(204);
        $this->assertDatabaseHas('periods',[
            'id'     => $period->id,
            'status' => EnumLessonStatus::CANCELED
        ]);
    }

    
    public function teacherConfirmCanceled()
    {
        $lesson = $this->createLesson();
        $period = $this->createPeriod($lesson, EnumLessonStatus::CANCELED);
        $response = $this->json('PATCH', '/api/lessons/'.$lesson->id.'/periods/'.$period->id, ["confirmed" => true], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(401);
    }

    public function teacherConfirmInProgress()
    {
        $lesson = $this->createLesson();
        $period = $this->createPeriod($lesson, EnumLessonStatus::IN_PROGRESS);
        $response = $this->json('PATCH', '/api/lessons/'.$lesson->id.'/periods/'.$period->id, ["confirmed" => true], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(401);
    }
    

    private function createLesson()
    {
        return factory(Lesson::class,1)->create([
                                            'teacher_id' => $this->teacher->id,
                                            'student_id' => $this->student->id,
                                            'status'     => EnumLessonStatus::IN_PROGRESS
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
    