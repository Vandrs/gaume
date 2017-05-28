<?php 

namespace App\Services\Lesson;

use Config;
use Gate;
use Log;
use App\Enums\EnumLessonStatus;
use App\Enums\EnumPolicy;
use App\Enums\EnumRole;
use App\Enums\EnumQueue;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Period;
use App\Services\Service;
use App\Exceptions\ValidationException;
use App\Exceptions\AuthorizationException;
use Carbon\Carbon;
use App\Jobs\CheckInProgressPeriod;
use App\Jobs\CheckPendingPeriod;

class CreatePeriodService extends Service
{

	public static function registerPolicies()
	{
		$service = new self();
		Gate::define(EnumPolicy::CREATE_PERIOD, function(User $user, Lesson $lesson) use ($service) {
			return $service->createPolicy($user, $lesson);
		});
	}

	public function createFromLesson(Lesson $lesson)
	{
		if ($lesson->status !== EnumLessonStatus::IN_PROGRESS) {
			throw new AuthorizationException(__('validation.custom.class_confirmation_expired'));
		}

		$period = Period::create([
			'lesson_id' => $lesson->id,
			'hours' => Config::get('lesson.lesson_time'),
			'hour_value' => Config::get('lesson.lesson_hour_value'),
			'status' => EnumLessonStatus::IN_PROGRESS
		]);

		$this->dispatchJobInProgress($period);

		return $period;
	}

	public function createToRenewLesson(Lesson $lesson)
	{
		if ($lesson->status !== EnumLessonStatus::IN_PROGRESS) {
			throw new AuthorizationException(__('validation.custom.class_confirmation_expired'));
		}

		$period = Period::create([
			'lesson_id' => $lesson->id,
			'hours' => Config::get('lesson.lesson_time'),
			'hour_value' => Config::get('lesson.lesson_hour_value'),
			'status' => EnumLessonStatus::PENDING
		]);

		$this->dispatchJobPending($period);

		return $period;
	}

	public function createPolicy(User $user, Lesson $lesson)
	{
		return $user->hasRole(EnumRole::STUDENT) && $user->id == $lesson->student_id;
	}

	private function dispatchJobInProgress(Period $period)
	{
		$job = new CheckInProgressPeriod($period);
		$minutes = Config::get('lesson.confirm_time');
		$minutes += 1;
		$delayTime = Carbon::now()->addHours($period->hours)
								  ->addMinutes($minutes);
		$job->delay($delayTime)
			->onQueue(EnumQueue::LESSON);
		dispatch($job);
	}

	private function dispatchJobPending(Period $period)
	{
		$job = new CheckPendingPeriod($period);
		$minutes = Config::get('lesson.confirm_time');
		$minutes += 1;
		$delayTime = Carbon::now()->addMinutes($minutes);
		$job->delay($delayTime)
			->onQueue(EnumQueue::LESSON);
		dispatch($job);
	}
}