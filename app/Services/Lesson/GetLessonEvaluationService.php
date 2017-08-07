<?php 

namespace App\Services\Lesson;

use App\Models\User;
use App\Models\LessonEvaluation;
use App\Enums\EnumRole;

class GetLessonEvaluationService 
{
	public static function get(User $user, $id)
	{
		$query = LessonEvaluation::query()->where('id', $id);
		if (!$user->hasRole(EnumRole::ADMIN)) {
			$query->where('user_id', $user->id);
		}
		return $query->firstOrFail();
	}
}