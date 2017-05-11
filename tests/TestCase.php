<?php

namespace Tests;

use Illuminate\Foundation\Testing\TestCase as BaseTestCase;
use App\Models\User;
use App\Enums\EnumRole;

abstract class TestCase extends BaseTestCase
{
    use CreatesApplication;

    protected $teacher;
    protected $student;
    protected $admin;

    protected function createUsers() 
    {
        $this->teacher = factory(User::class, 1)->create(['role_id' => EnumRole::TEACHER_ID])->first();
        $this->student = factory(User::class, 1)->create(['role_id' => EnumRole::STUDENT_ID])->first();
        $this->admin   = factory(User::class, 1)->create(['role_id' => EnumRole::ADMIN_ID])->first();
    }

    protected function getHeadersApiToken(User $user)
    {
        $token = $user->createToken('Token'.$user->id)->accessToken;
        return ['Authorization' => 'Bearer ' . $token];
        
    }

    public function setUp()
    {
    	parent::setUp();
    	$this->createUsers();
    }
}
