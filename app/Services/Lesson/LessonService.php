<?php 

namespace App\Services\Lesson;

use Gate;
use Config;
use Validator;
use Log;
use DB;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Services\Service;
use App\Services\Lesson\PeriodService;
use App\Enums\EnumPolicy;
use App\Enums\EnumRole;
use App\Enums\EnumLessonStatus;
use App\Exceptions\ValidationException;
use App\Exceptions\AuthorizationException;
use App\Models\Lesson;
use Carbon\Carbon;

class LessonService extends Service
{
	public static function registerPolicies()
	{
		$service = new self();
		Gate::define(EnumPolicy::CREATE_LESSON, function(User $user) use ($service) {
			return $service->createPolicy($user);
		});
		Gate::define(EnumPolicy::CONFIRM_LESSON, function(User $user, Lesson $lesson) use ($service) {
			return $service->confirmPolicy($user, $lesson);
		});
	}

	public function create(User $student, array $data)
	{
		$this->validator = Validator::make(
								$data, 
								$this->createValidation(), 
								$this->createValidationMessages()
							);
		if ($this->validator->fails()) {
			throw new ValidationException('FALHA AO VALIDAR: '.json_encode($this->validator->errors()->all()));
		}
		return Lesson::create([
			'teacher_id' => $data['teacher_id'],
			'student_id' => $student->id,
			'status'	 => EnumLessonStatus::PENDING
		]);
	}

	public function confirm(Lesson $lesson, array $data)
	{
		$validator = $this->validator = Validator::make(
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
					$periodService = new PeriodService();
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

	public function createPolicy(User $user)
	{
		return $user->hasRole(EnumRole::STUDENT);
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

	private function createValidation()
	{
		return [
			'teacher_id' => [
				'required',
				'integer',
				Rule::exists('users','id')->where('role_id', EnumRole::TEACHER_ID)
			]
		];
	}

	private function confirmValidation()
	{
		return [
			'confirmed' => "required|boolean"
		];
	}

	private function createValidationMessages()
	{
		return [
			'teacher_id.required' => __('validation.required',['attribute' => 'teacher_id']),
			'teacher_id.integer'  => __('validation.integer',['attribute' => 'teacher_id']),
			'teacher_id.exists'   => __('validation.custom.is_not_teacher')
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