<?php 

namespace App\Services\Registration;

use Validator;
use Config;
use DB;
use Carbon\Carbon;
use App\Services\Service;
use App\Exceptions\ValidationException;
use App\Models\User;
use App\Utils\StringUtil;
use App\Services\Location\CreateAddressService;
use App\Services\User\UserProfilePhotoService;
use App\Enums\EnumUserStatus;
use App\Enums\EnumRole;

class StudentRegistrationService extends Service
{
	public static function registerPolicies() {}

	public function registerUser (array $data, $profileImage = null) 
	{
		$data['role_id'] = EnumRole::STUDENT_ID;
		$this->validator = Validator::make($data, $this->getRegistrationRules(), $this->getRegistrationMessages());
		if ($this->validator->fails()) {
			throw new ValidationException();
		}
		
		try {
			$data['status'] = EnumUserStatus::ACTIVE;
			$user = $this->createUser($data);
			$addressService = new CreateAddressService();
			$address = $addressService->create($user, $data);
			if ($profileImage) {
				$photoUploadService = new UserProfilePhotoService();
				$photoUploadService->uploadPhoto($user, $profileImage);
			}
			return $user;
		} catch(ValidationException $e) {
				$this->validator->messages()->merge($addressService->getValidator()->messages());				
				if (isset($photoUploadService)) {
					$this->validator->messages()->merge($photoUploadService->getValidator()->messages());	
				}
			throw $e;
		}
	}

	public function getRegistrationRules()
	{

		$now = Carbon::now();
		$now->subYears(Config::get('app.min_age_student'));

		return [
			'cpf' 		 => 'required|cpf',
			'name' 		 => 'required|string|max:255',
			'nickname' 	 => 'required|max:20|unique:users',
            'email' 	 => 'required|email|max:255|unique:users',
            'birth_date' => 'required|date|dateBeforeOrEqual:'.$now->format('Y-m-d'),
            'password' 	 => 'required|min:8|confirmed',
            'role_id' 	 => 'required|integer|exists:roles,id',
            'terms' 	 => 'required'
		];
	}

	public function getRegistrationMessages()
	{
		return [
			'cpf.required' 	 			      => __('validation.required', ['attribute' => __('site.registration.cpf')]),
			'cpf.cpf' 		 			      => __('validation.cpf'),
			'name.required'  			      => __('validation.required', ['attribute' => __('site.registration.name')]),
			'name.string' 	 			      => __('validation.string', ['attribute' => __('site.registration.name')]),
			'name.max' 		 			      => __('validation.max.string', ['attribute' => __('site.registration.name'), 'max' => 255]),
			'nickname.required' 		      => __('validation.required', ['attribute' => __('site.registration.nickname')]),
			'nickname.max' 				      => __('validation.max.string', ['attribute' => __('site.registration.nickname'), 'max' => 20]),
			'nickname.unique' 			      => __('validation.unique', ['attribute' => __('site.registration.nickname')]),
			'email.required' 			      => __('validation.required', ['attribute' => __('site.registration.email')]),
			'email.email'	 			      => __('validation.email', ['attribute' => __('site.registration.email')]),
			'email.max'		 			      => __('validation.max.string', ['attribute' => __('site.registration.email'), 'max' => 255]),
			'email.unique'	 			      => __('validation.unique', ['attribute' => __('site.registration.email')]),
			'birth_date.required'		      => __('validation.required', ['attribute' => __('site.registration.birth_date')]),
			'birth_date.date'			      => __('validation.date', ['attribute' => __('site.registration.birth_date')]),
			'birth_date.date_before_or_equal' => __('validation.custom.min_age', ['age' => Config::get('app.min_age_student')]),
			'password.required'			      => __('validation.required', ['attribute' => __('site.registration.password')]),
			'password.min'				      => __('validation.min.string', ['attribute' => __('site.registration.password'), 'min' => 8]),
			'password.confirmed'		      => __('validation.confirmed', ['attribute' => __('site.registration.password')]),
			'terms.required' 				  => __('validation.custom.terms')
		];
	}

	private function createUser($data)
	{

		$format = str_contains($data['birth_date'], '/') ? 'd/m/Y H:i:s' : 'Y-m-d H:i:s';

		return User::create([
			'cpf' => StringUtil::justNumbers($data['cpf']),
			'name' => $data['name'],
			'nickname' => $data['nickname'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
			'birth_date'=> Carbon::createFromFormat($format, $data['birth_date']),
			'role_id' => $data['role_id']
		]);
	}

}
