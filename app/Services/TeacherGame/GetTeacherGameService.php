<?php

namespace App\Services\TeacherGame;

use App\Models\User;
use App\Models\TeacherGame;

class GetTeacherGameService
{
	public static function getAll(User $user)
	{
		return TeacherGame::with(['game','teacherGamePlatforms.platform'])
						  ->get();		
	}
}