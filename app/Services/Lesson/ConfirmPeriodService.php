<?php 

namespace App\Services\Lesson;

use Config;
use Gate;
use App\Enums\EnumLessonStatus;
use App\Enums\EnumPolicy;
use App\Enums\EnumRole;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Period;
use App\Services\Service;
use App\Exceptions\ValidationException;
use App\Exceptions\AuthorizationException;

class ConfirmPeriodService extends Service
{

	public static function registerPolicies()
	{
		$service = new self();
		Gate::define(EnumPolicy::CONFIRM_PERIOD, function(User $user, Lesson $lesson, Period $period) use ($service) {
			return $service->createPolicy($user, $lesson, $period);
		});
	}

	public function confirm(Period $period, array $data)
	{

	}

	public function confirmPolicy(User $user, Lesson $lesson, Period $period)
	{
		return ($user->hasRole(EnumRole::TEACHER) && 
			    $user->id === $lesson->teacher_id) &&
				$period->lesson_id === $lesson->id;
	}

}