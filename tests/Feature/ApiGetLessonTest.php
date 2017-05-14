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

class ApiGetLessonTest extends TestCase
{
    use DatabaseTransactions;

    public function testUnauthorizedAccess()
    {
        $lesson = $this->createLesson();
        $response = $this->json('GET','/api/lessons/'.$lesson->id);
        $response->assertStatus(401);
    }

    public function testNotFound()
    {
        $lesson = $this->createLesson();
        $response = $this->json('GET','/api/lessons/'.($lesson->id+1), [], $this->getHeadersApiToken($this->student));
        $response->assertStatus(404);
    }

    public function testGetSimpleLesson()
    {
        $lesson = $this->createLesson();
        $response = $this->json('GET','/api/lessons/'.($lesson->id), [], $this->getHeadersApiToken($this->student));
        $response->assertStatus(200);
        $response->json();
    }


    public function testGetSimpleIncludes()
    {
        $lesson = $this->createLesson();
        $this->createPeriods($lesson, 5);
        $response = $this->json('GET','/api/lessons/'.($lesson->id), ['includes' => 'periods,teacher,student'], $this->getHeadersApiToken($this->student));
        $response->assertStatus(200);    
        $response->json();
    }

    public function testGetWrongUser()
    {
        $lesson = $this->createLesson();
        $response = $this->json('GET','/api/lessons/'.($lesson->id), [], $this->getHeadersApiToken($this->createUserWithRole(EnumRole::STUDENT_ID)));
        $response->assertStatus(401);
    }


    private function createLesson($status = null)
    {
        return factory(Lesson::class,1)->create([
                                            'teacher_id' => $this->teacher->id,
                                            'student_id' => $this->student->id,
                                            'status'     => empty($status) ? EnumLessonStatus::PENDING : $status
                                        ])
                                       ->first();
    }

    private function createPeriods(Lesson $lesson, $qtd =5)
    {
        return factory(Period::class,$qtd)->create([
                                            'lesson_id' => $lesson->id,
                                            'status'    => EnumLessonStatus::IN_PROGRESS
                                        ]);
    }

}