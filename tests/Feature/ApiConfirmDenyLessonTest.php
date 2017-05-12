<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Config;
use App\Models\Lesson;
use App\Enums\EnumRole;
use App\Enums\EnumLessonStatus;

class ApiConfirmDenyLessonTest extends TestCase
{

    use DatabaseTransactions;

    public function testUnauthorizedAccess()
    {
        $lesson = $this->createLesson();
        $response = $this->json('PATCH','/api/lessons/'.$lesson->id);
        $response->assertStatus(401);
    }

    public function testNotFound()
    {
        $lesson = $this->createLesson();
        $response = $this->json('PATCH','/api/lessons/'.($lesson->id+1), [], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(404);
    }

    public function testStudentConfirm()
    {
        $lesson = $this->createLesson();
        $response = $this->json('PATCH','/api/lessons/'.$lesson->id, ['confirmed' => true], $this->getHeadersApiToken($this->student));
        $response->assertStatus(401);
    }


    public function testStudentDeny()
    {
        $lesson = $this->createLesson();
        $response = $this->json('PATCH','/api/lessons/'.$lesson->id, ['confirmed' => false], $this->getHeadersApiToken($this->student));
        $response->assertStatus(401);
    }

    public function testTeacherConfirmBadRequest()
    {
        $lesson = $this->createLesson();
        $response = $this->json('PATCH', '/api/lessons/'.$lesson->id, ["confirmed" => ""], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(400);
        $response = $this->json('PATCH', '/api/lessons/'.$lesson->id, ["confirmed"], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(400);
        $response = $this->json('PATCH', '/api/lessons/'.$lesson->id, [], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(400);
    }

    public function testTeacherConfirm()
    {
        $lesson = $this->createLesson();
        $response = $this->json('PATCH', '/api/lessons/'.$lesson->id, ["confirmed" => true], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(204);
        $this->assertDatabaseHas('lessons',[
            'id'     => $lesson->id,
            'status' => EnumLessonStatus::IN_PROGRESS
        ]);
        $this->assertDatabaseHas('periods',[
            'lesson_id'     => $lesson->id,
            'status' => EnumLessonStatus::IN_PROGRESS
        ]);        
    }

/*
    public function testTeacherConfirmExpired()
    {
        $lesson = $this->createLesson();
        $confirmationTime = Config::get('lesson.confirm_time');
        sleep(140);
        $response = $this->json('PATCH', '/api/lessons/'.$lesson->id, ["confirmed" => true], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(401);
        $this->assertDatabaseHas('lessons',[
            'id'     => $lesson->id,
            'status' => EnumLessonStatus::PENDING
        ]);
    }
*/

    public function testTeacherDeny()
    {
        $lesson = $this->createLesson();
        $response = $this->json('PATCH', '/api/lessons/'.$lesson->id, ["confirmed" => false], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(204);
        $this->assertDatabaseHas('lessons',[
            'id'     => $lesson->id,
            'status' => EnumLessonStatus::CANCELED
        ]);
    }


    private function createLesson()
    {
        return factory(Lesson::class,1)->create([
                                            'teacher_id' => $this->teacher->id,
                                            'student_id' => $this->student->id,
                                            'status'     => EnumLessonStatus::PENDING
                                        ])
                                       ->first();
    }
}
    