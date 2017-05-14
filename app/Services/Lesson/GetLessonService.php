<?php 

namespace App\Services\Lesson;

use Gate;
use App\Services\Service;
use App\Enums\EnumPolicy;
use App\Enums\EnumRole;
use App\Models\User;
use App\Models\Lesson;


class GetLessonService extends Service
{
	public static function registerPolicies()
	{
		$service = new self();
		Gate::define(EnumPolicy::GET_LESSON, function(User $user, Lesson $lesson) use ($service) {
			return $service->getPolicy($user, $lesson);
		});
	}

	public function getPolicy(User $user, Lesson $lesson)
	{
		if ($user->hasRole(EnumRole::ADMIN)) {
			return true;
		}
		return in_array($user->id,[$lesson->teacher_id, $lesson->student_id]);
	}

	public function get($id, $includes = null)
	{
		if ($includes) {
			return Lesson::with(explode(',', $includes))
						 ->where('id', $id)
						 ->firstOrFail();
		} else {
			return Lesson::findOrfail($id);
		}
	}
}