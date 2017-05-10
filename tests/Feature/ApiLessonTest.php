<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use App\Models\User;
use App\Enums\EnumRole;

class ApiLessonTest extends TestCase
{

    use DatabaseTransactions;

    public function testCreateLesson()
    {
        $this->createUsers();
    	$response = $this->json('POST','/api/lessons');
    	$response->assertStatus(401);
        $response = $this->json('POST', '/api/lessons', [], $this->getHeadersApiToken($this->teacher));
        $response->assertStatus(401);
        $response = $this->json('POST','/api/lessons', [] , $this->getHeadersApiToken($this->student));
        $response->assertStatus(400);
    }
}
