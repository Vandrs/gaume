<?php 

namespace App\Services\Registration;

use Hash;
use Validator;
use Carbon\Carbon;
use App\Services\Service;
use App\Services\Registration\SavePreRegistrationPlatformService;
use App\Services\Registration\SendPreRegistrationEmailService;
use App\Models\PreRegistration;
use App\Exceptions\ValidationException;

class CreatePreRegistrationService extends Service
{
	public static function registerPolicies() {}

	public function create(array $data) 
	{
		$this->validator = Validator::make($data, $this->getValidation(), $this->getMessages());
		if ($this->validator->fails()) {
			throw new ValidationException();
		}

		$preRegistration = PreRegistration::create([
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

		SendPreRegistrationEmailService::send($preRegistration);

		return $preRegistration;
	}

	private function getValidation()
	{
		return [
			'name' 			=> 'required',
			'email' 		=> 'required|email|unique:users|unique:pre_registration',
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