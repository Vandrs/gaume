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

class ApiCreatePeriodTest extends TestCase
{
    use DatabaseTransactions;

    public function testUnauthorizedAccess()
    {
        $lesson = $this->createLesson();
        $response = $this->json('POST','/api/lessons/'.$lesson->id."/periods");
        $response->assertStatus(401);
    }

    public function testNotFound()
    {
        $lesson = $this->createLesson();
        $response = $this->json('POST','/api/lessons/'.($lesson->id+1)."/periods", [], $this->getHeadersApiToken($this->student));
        $response->assertStatus(404);
    }

    public function testCreateTeacher()
    {
    	$lesson = $this->createLesson();
        $response = $this->json('POST','/api/lessons/'.$lesson->id."/periods", [], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(401);
    }

    public function testCreateWrongUser()
    {
    	$lesson = $this->createLesson(EnumLessonStatus::IN_PROGRESS);
        $response = $this->json(
        	'POST',
        	'/api/lessons/'.$lesson->id."/periods", 
        	[], 
        	$this->getHeadersApiToken($this->createUserWithRole(EnumRole::STUDENT_ID))
        );
        $response->assertStatus(401);
    }

    public function testCreatePending()
    {
    	$lesson = $this->createLesson(EnumLessonStatus::PENDING);
        $response = $this->json(
        	'POST',
        	'/api/lessons/'.$lesson->id."/periods", 
        	[], 
        	$this->getHeadersApiToken($this->student)
        );
        $response->assertStatus(401);
    }

    public function testCreateCanceled()
    {
    	$lesson = $this->createLesson(EnumLessonStatus::CANCELED);
        $response = $this->json(
        	'POST',
        	'/api/lessons/'.$lesson->id."/periods", 
        	[], 
        	$this->getHeadersApiToken($this->student)
        );
        $response->assertStatus(401);
    }

    public function testCreateFinished()
    {
    	$lesson = $this->createLesson(EnumLessonStatus::FINISHED);
        $response = $this->json(
        	'POST',
        	'/api/lessons/'.$lesson->id."/periods", 
        	[], 
        	$this->getHeadersApiToken($this->student)
        );
        $response->assertStatus(401);
    }

    public function testCreateStudent()
    {
    	$lesson = $this->createLesson(EnumLessonStatus::IN_PROGRESS);
        $response = $this->json(
        	'POST',
        	'/api/lessons/'.$lesson->id."/periods", 
        	[], 
        	$this->getHeadersApiToken($this->student)
        );
        $response->assertStatus(201);
        $response->assertSeeText('id');
        $data = $response->json();
        $this->assertDatabaseHas('periods',[
        	'id' => $data['id'],
            'lesson_id'     => $lesson->id,
            'status' => EnumLessonStatus::PENDING
        ]);
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

}