<?php 

namespace App\Services\Lesson;

use Gate;
use Config;
use Validator;
use Log;
use Illuminate\Validation\Rule;
use App\Models\User;
use App\Services\Service;
use App\Enums\EnumPolicy;
use App\Enums\EnumRole;
use App\Exceptions\ValidationException;
use App\Exceptions\AuthorizationException;

class LessonService extends Service
{
	public static function registerPolicies()
	{
		$service = new self();
		Gate::define(EnumPolicy::CREATE_LESSON, function(User $user) use ($service) {
			return $service->createPolicy($user);
		});
	}

	public function create(User $user, array $data)
	{
		$this->validator = Validator::make(
								$data, 
								$this->createValidation(), 
								$this->createValidationMessages()
							);

		if ($this->validator->fails()) {
			throw new ValidationException;
		}
		// CRIA AULA E FICA AGUARDANDO CONFIRMAÇÃO 
	}	

	public function createPolicy(User $user)
	{
		Log::info('HAS ROLE '.EnumRole::STUDENT. " ".$user->hasRole(EnumRole::STUDENT));
		Log::info('ID '.$user->id);
		Log::info('ROLE '.$user->role->role);
		Log::info('NAME '.$user->name);
		return $user->hasRole(EnumRole::STUDENT);
	}

	private function createValidation()
	{
		return [
			'teacher_id' => [
				'required',
				'integer',
				Rule::exists('users')->where('role_id', EnumRole::TEACHER)
			]
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