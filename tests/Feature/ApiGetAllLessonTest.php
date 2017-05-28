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

class ApiGetAllLessonTest extends TestCase
{
    use DatabaseTransactions;

    public function testUnauthorizedAccess()
    {
        $response = $this->json('GET','/api/lessons');
        $response->assertStatus(401);
    }

    

    public function testGetLessonStudent()
    {
        $lessons = $this->createLessons(10);
        $testCase = $this;
        $lessons->each(function ($lesson) use ($testCase) {
            $testCase->createPeriods($lesson, 3);
        });
        $response = $this->json('GET','/api/lessons', [], $this->getHeadersApiToken($this->student));
        $response->assertStatus(200);
        $data = $response->json();
    }

    
    public function testGetLessonTeacher()
    {
        $lessons = $this->createLessons(10);
        $testCase = $this;
        $lessons->each(function ($lesson) use ($testCase) {
            $testCase->createPeriods($lesson, 3);
        });
        $response = $this->json('GET','/api/lessons', [], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(200);
        $data = $response->json();
    }
    

    private function createLessons($qtd = 5)
    {
        return factory(Lesson::class, $qtd)->create([
                                            'teacher_id' => $this->teacher->id,
                                            'student_id' => $this->student->id,
                                            'status'     => empty($status) ? EnumLessonStatus::PENDING : $status
                                        ])
                                       ->first();
    }

    private function createPeriods(Lesson $lesson, $qtd = 5)
    {
        return factory(Period::class,$qtd)->create([
                                            'lesson_id' => $lesson->id,
                                            'status'    => EnumLessonStatus::IN_PROGRESS
                                        ]);
    }

}