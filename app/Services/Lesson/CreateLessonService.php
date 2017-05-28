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
use App\Enums\EnumPolicy;
use App\Enums\EnumRole;
use App\Enums\EnumLessonStatus;
use App\Enums\EnumQueue;
use App\Exceptions\ValidationException;
use App\Exceptions\AuthorizationException;
use App\Models\Lesson;
use App\Jobs\CheckPendingLesson;
use Carbon\Carbon;

class CreateLessonService extends Service
{
	public static function registerPolicies()
	{
		$service = new self();
		Gate::define(EnumPolicy::CREATE_LESSON, function(User $user) use ($service) {
			return $service->createPolicy($user);
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

		$lesson = Lesson::create([
			'teacher_id' => $data['teacher_id'],
			'student_id' => $student->id,
			'status'	 => EnumLessonStatus::PENDING
		]);

		$this->dispatchJob($lesson);		

		return $lesson;
	}

	private function dispatchJob(Lesson $lesson)
	{
		$delayJobTime = Carbon::now()->addMinutes(Config::get('lesson.confirm_time'));
		$job = new CheckPendingLesson($lesson);
		$job->delay($delayJobTime)
			->onQueue(EnumQueue::LESSON);
		dispatch($job);
	}

	
	public function createPolicy(User $user)
	{
		return $user->hasRole(EnumRole::STUDENT);
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

}