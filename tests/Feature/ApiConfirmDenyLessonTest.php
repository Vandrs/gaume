<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\Lesson;
use App\Enums\EnumRole;
use App\Enums\EnumLessonStatus;

class ApiConfirmDenyLessonTest extends TestCase
{

    use DatabaseTransactions;

    private $lessons;

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

/*
    public function testStudentDeny()
    {

    }

    public function testTeacherConfirm()
    {
        $response = $this->json('POST', '/api/lessons', [], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(401);
    }

    public function testTeacherDeny()
    {

    }
*/
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
    