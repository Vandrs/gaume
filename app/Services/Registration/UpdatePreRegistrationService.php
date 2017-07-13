<?php 

namespace App\Services\Registration;

use Hash;
use Validator;
use Carbon\Carbon;
use App\Services\Service;
use App\Services\Registration\SavePreRegistrationPlatformService;
use App\Models\PreRegistration;
use App\Exceptions\ValidationException;
use Illuminate\Validation\Rule;

class UpdatePreRegistrationService extends Service
{
	public static function registerPolicies() {}

	public function update(PreRegistration $preRegistration, array $data) 
	{
		$this->validator = Validator::make($data, $this->getValidation($preRegistration), $this->getMessages());
		if ($this->validator->fails()) {
			throw new ValidationException();
		}

		$preRegistration->update([
			"name" => $data["name"],
			"email" => $data['email'],
			"code" => self::generateCode($data['email'])
		]);

		try {
			$registrationPlatformService = new SavePreRegistrationPlatformService();
			$registrationPlatformService->save($preRegistration, $data['gamePlatforms']);
		} catch (ValidationException $e) {
			$this->validator->messages()->merge($registrationPlatformService->getValidator()->messages());
			throw $e;
		}
		
		return $preRegistration;
	}

	private function getValidation(PreRegistration $preRegistration)
	{
		return [
			'name'  => 'required',
			'email' => [
				'required',
				'email',
				 Rule::unique('pre_registration')->ignore($preRegistration->id),
				'unique:users'
			],
			'gamePlatforms' => 'required|array'
		];
	}

	private function getMessages()
	{
		return [
			'name.required'  	 	 => __('validation.required', ['attribute' => 'Nome']),
			'email.required' 	 	 => __('validation.required', ['attribute' => 'E-mail']),
			'email.email' 	 	 	 => __('validation.email', ['attribute' => 'E-mail']),
			'email.unique' 	 	 	 => __('validation.unique', ['attribute' => 'E-mail']),
			'gamePlatforms.required' => __('validation.required', ['attribute' => 'Jogos']),
			'gamePlatforms.array' 	 => __('validation.array', ['attribute' => 'Jogos'])
		];
	}

	public static function generateCode($key)
	{
		$salt = Carbon::now()->format('YmdHis');
		return Hash::make($salt.$key);
	}


}