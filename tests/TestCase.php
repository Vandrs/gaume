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
        $this->teacher = $this->createUserWithRole(EnumRole::TEACHER_ID);
        $this->student = $this->createUserWithRole(EnumRole::STUDENT_ID);
        $this->admin   = $this->createUserWithRole(EnumRole::ADMIN_ID);
    }

    protected function createUserWithRole($role)
    {
        return factory(User::class, 1)->create(['role_id' => $role])->first();
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
