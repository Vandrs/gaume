<?php 

namespace App\Services\User;

use App\Services\Service;
use Validator;
use Carbon\Carbon;
use Config;
use App\Exceptions\ValidationException;

class UserRegistrationService extends Service
{
	public static function registerPolicies() {}

	public function registerUser (array $data, $profileImage = null) 
	{
		$this->validator = Validator::make($data, $this->getRegistrationRules(), $this->getRegistrationMessages());
		if ($this->validator->fails()) {
			throw new ValidationException();
		}
		echo '<pre>';
		print_r($data);
		die;
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

}