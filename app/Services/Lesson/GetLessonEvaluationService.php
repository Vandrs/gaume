<?php 

namespace App\Services\Lesson;

use App\Models\User;
use App\Models\LessonEvaluation;
use App\Enums\EnumRole;
use App\Enums\EnumLessonStatus;

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

	public static function getEvaluationNotes(User $user)
	{
		$query = LessonEvaluation::query()->select('lesson_evaluations.*')
									      ->join('lessons','lesson_evaluations.lesson_id', '=', 'lessons.id')
								 		  ->where('lesson_evaluations.user_id', '<>', $user->id)
								 		  ->where('lessons.status', '=', EnumLessonStatus::FINISHED)
								 		  ->whereNotNull('note');
		if ($user->hasRole(EnumRole::TEACHER)) {
			$query->where('lessons.teacher_id', '=', $user->id);
		} else if ($user->hasRole(EnumRole::STUDENT)) {
			$query->where('lessons.student_id', '=', $user->id);
		}
		$query->orderBy('lessons.created_at','DESC');
		return $query->paginate(10);
	}

}