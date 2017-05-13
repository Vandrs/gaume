<?php 

namespace App\Services\Lesson;

use Config;
use Gate;
use DB;
use Validator;
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
		DB::beginTransaction();
		try {
			if ($data['confirmed']) {
				$period->update(['status' => EnumLessonStatus::IN_PROGRESS]);
				$period->lesson->update(['status' => EnumLessonStatus::IN_PROGRESS]);
			} else {
				$period->update(['status' => EnumLessonStatus::CANCELED]);
			}
			DB::commit();
		} catch (\Exception $e) {
			DB::rollback();
			throw $e;
		}
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