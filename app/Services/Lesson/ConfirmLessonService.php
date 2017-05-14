<?php 

namespace App\Services\Lesson;

use Gate;
use Config;
use Validator;
use Log;
use DB;
use App\Models\User;
use App\Services\Service;
use App\Services\Lesson\CreatePeriodService;
use App\Enums\EnumPolicy;
use App\Enums\EnumRole;
use App\Enums\EnumLessonStatus;
use App\Exceptions\ValidationException;
use App\Exceptions\AuthorizationException;
use App\Models\Lesson;
use Carbon\Carbon;

class ConfirmLessonService extends Service
{
	public static function registerPolicies()
	{
		$service = new self();
		Gate::define(EnumPolicy::CONFIRM_LESSON, function(User $user, Lesson $lesson) use ($service) {
			return $service->confirmPolicy($user, $lesson);
		});
	}

	public function confirm(Lesson $lesson, array $data)
	{
		$this->validator = Validator::make(
								$data, 
								$this->confirmValidation(), 
								$this->confirmValidationMessages()
							);
		if ($this->validator->fails()) {
			throw new ValidationException('FALHA AO VALIDAR: '.json_encode($this->validator->errors()->all()));
		}
		
		if ($data['confirmed']) {
			$isPending = $lesson->status === EnumLessonStatus::PENDING;
			$isConfirmPeriod = $this->isInConfirmationPeriod($lesson);
			if (in_array(false, [$isPending, $isConfirmPeriod])) {
				throw new AuthorizationException(__('validation.custom.class_confirmation_expired'));
			} else {
				try {
					DB::beginTransaction();
					$periodService = new CreatePeriodService();
					$result = $lesson->update(['status' => EnumLessonStatus::IN_PROGRESS]);
					$periodService->createFromLesson($lesson);
					DB::commit();
					return $result;
				} catch (\Exception $e) {
					DB::rollback();
					throw $e;
				}
			}
		} else {
			return $lesson->update(['status' => EnumLessonStatus::CANCELED]);
		}
	}


	public function confirmPolicy(User $user, Lesson $lesson)
	{
		return ($user->hasRole(EnumRole::TEACHER) && $user->id === $lesson->teacher_id);
	}

	public function isInConfirmationPeriod(Lesson $lesson)
	{
		$confirmLimit = Carbon::createFromTimestamp($lesson->created_at->getTimestamp())
							  ->addMinutes(Config::get('lesson.confirm_time'));
		return $confirmLimit->greaterThanOrEqualTo(Carbon::now());
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