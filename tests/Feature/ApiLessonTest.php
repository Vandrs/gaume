<?php

namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\WithoutMiddleware;
use Illuminate\Foundation\Testing\DatabaseMigrations;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ApiLessonTest extends TestCase
{
    /**
     * A basic test example.
     *
     * @return void
     */
    public function testCreateLesson()
    {
    	$response = $this->json('POST','/api/lessons');
    	$response->assertStatus(201);
    }
}
