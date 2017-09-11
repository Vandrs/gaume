<?php 

namespace App\Services\Lesson;

use Config;
use Gate;
use DB;
use Validator;
use App\Enums\EnumLessonStatus;
use App\Enums\EnumPolicy;
use App\Enums\EnumRole;
use App\Enums\EnumQueue;
use App\Models\User;
use App\Models\Lesson;
use App\Models\Period;
use App\Services\Service;
use App\Services\Lesson\EndLessonService;
use App\Services\Lesson\EndPeriodService;
use App\Exceptions\ValidationException;
use App\Exceptions\AuthorizationException;
use Carbon\Carbon;
use App\Jobs\CheckInProgressPeriod;

class ConfirmPeriodService extends Service
{

	public static function registerPolicies()
	{
		$service = new self();
		Gate::define(EnumPolicy::CONFIRM_PERIOD, function(User $user, Period $period) use ($service) {
			return $service->confirmPolicy($user, $period);
		});
	}

	public function confirm(Period $period, array $data)
	{
		$this->validator = Validator::make($data, $this->confirmValidation(), $this->confirmValidationMessages());
		if ($this->validator->fails()) {
			throw new ValidationException();
		}
		if ($data['confirmed']) {
			$period->update(['status' => EnumLessonStatus::IN_PROGRESS]);
			$period->lesson->update(['status' => EnumLessonStatus::IN_PROGRESS]);
			$this->dispatchJob($period);
		} else {
			if ($period->status != EnumLessonStatus::CANCELED) {
				$endPeriod = new EndPeriodService();
				$endPeriod->cancelPeriod($period);
			}
		}
	}

	private function dispatchJob(Period $period)
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

	public function confirmPolicy(User $user, Period $period)
	{
		$isTeacher = $user->hasRole(EnumRole::TEACHER);
		$isLessonTeacher = $user->id === $period->lesson->teacher_id;
		$isPeriodPending = $period->status === EnumLessonStatus::PENDING;
		return !in_array(false, [$isTeacher, $isLessonTeacher, $isPeriodPending]);
	}

	private function confirmValidation()
	{
		return [
			'confirmed' => "required|boolean"
		];
	}

	private function confirmValidationMessages()
	{
		return [
			'confirmed.required' => __('validation.required',['attribute' => 'confirmed']),
			'confirmed.boolean'  => __('validation.boolean',['attribute' => 'confirmed'])
		];
	}

}