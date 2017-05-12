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

class PeriodService extends Service
{

	public static function registerPolicies()
	{
		$service = new self();
		Gate::define(EnumPolicy::CREATE_PERIOD, function(User $user, Lesson $lesson) use ($service) {
			return $service->createPolicy($user, $lesson);

		});
		Gate::define(EnumPolicy::CONFIRM_PERIOD, function(User $user, Lesson $lesson, Period $period) use ($service) {
			return $service->createPolicy($user, $lesson, $period);
		});
	}

	public function createFromLesson(Lesson $lesson)
	{
		if ($lesson->status !== EnumLessonStatus::IN_PROGRESS) {
			throw new AuthorizationException(__('validation.custom.class_confirmation_expired'));
		}
		return Period::create([
			'lesson_id' => $lesson->id,
			'hours' => Config::get('lesson.lesson_time'),
			'hour_value' => Config::get('lesson.lesson_hour_value'),
			'status' => EnumLessonStatus::IN_PROGRESS
		]);
	}

	public function createToRenewLesson(Lesson $lesson)
	{
		if ($lesson->status !== EnumLessonStatus::IN_PROGRESS) {
			throw new AuthorizationException(__('validation.custom.class_confirmation_expired'));
		}
		return Period::create([
			'lesson_id' => $lesson->id,
			'hours' => Config::get('lesson.lesson_time'),
			'hour_value' => Config::get('lesson.lesson_hour_value'),
			'status' => EnumLessonStatus::PENDING
		]);
	}

	public function confirm(Period $period, array $data)
	{

	}


	public function createPolicy(User $user, Lesson $lesson)
	{
		return $user->hasRole(EnumRole::STUDENT) && $user->id === $lesson->student_id;
	}

	public function confirmPolicy(User $user, Lesson $lesson, Period $period)
	{
		return ($user->hasRole(EnumRole::TEACHER) && 
			    $user->id === $lesson->teacher_id) &&
				$period->lesson_id === $lesson->id;
	}

}