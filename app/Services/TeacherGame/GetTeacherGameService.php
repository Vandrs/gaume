<?php

namespace App\Services\TeacherGame;

use App\Models\User;
use App\Models\TeacherGame;

class GetTeacherGameService
{
	public static function getAll(User $user = null)
	{
		$query = TeacherGame::with(['game','teacherGamePlatforms.platform']);
		if ($user) {
			$query->where('teacher_id', $user->id);
		}
		return $query->get();		
	}
}