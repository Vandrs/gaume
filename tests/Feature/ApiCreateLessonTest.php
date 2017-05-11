<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Enums\EnumRole;
use App\Enums\EnumLessonStatus;

class ApiCreateLessonTest extends TestCase
{

    use DatabaseTransactions;

    public function testTeacherCreate()
    {
        $response = $this->json('POST', '/api/lessons', [], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(401);
    }

    public function testStudentCreate()
    {
        $response = $this->json('POST','/api/lessons', [] , $this->getHeadersApiToken($this->student));
        $response->assertStatus(400);
        $response->assertJson(['errors'=>[]]);

        $response = $this->json('POST','/api/lessons', ['teacher_id' => $this->student->id] , $this->getHeadersApiToken($this->student));
        $response->assertStatus(400);
        $response->assertJson(['errors'=>[]]);

        $response = $this->json('POST','/api/lessons', ['teacher_id' => $this->teacher->id] , $this->getHeadersApiToken($this->student));
        $response->assertStatus(201);
        $response->assertSeeText('id');
        $data = $response->json();
        $this->assertDatabaseHas('lessons',[
            'id'     => $data['id'],
            'status' => EnumLessonStatus::PENDING
        ]);
    }

    public function testUnauthorizedUserCreate()
    {
        $response = $this->json('POST','/api/lessons');
        $response->assertStatus(401);
    }
}
    