<?php 

namespace App\Services\User;

use Validator;
use Config;
use DB;
use Carbon\Carbon;
use App\Services\Service;
use App\Exceptions\ValidationException;
use App\Models\User;
use App\Utils\StringUtil;
use App\Services\Location\CreateAddressService;

class UserRegistrationService extends Service
{
	public static function registerPolicies() {}

	public function registerUser (array $data, $profileImage = null) 
	{
		$this->validator = Validator::make($data, $this->getRegistrationRules(), $this->getRegistrationMessages());
		if ($this->validator->fails()) {
			throw new ValidationException();
		}

		try {
			DB::beginTransaction();
			$user = $this->createUser($data);
			$addressService = new CreateAddressService();
			$address = $addressService->create($user, $data);
			$user->address = $address;
			echo '<pre>';
			print_r($user);
			die;
			DB::commit();
			return $user;
		} catch(ValidationException $e) {
			$this->validator = $addressService->getValidator();
			DB::rollback();
			throw $e;
		} catch (\Exception $e) {
			DB::rollback();
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
		return User::create([
			'cpf' => StringUtil::justNumbers($data['cpf']),
			'name' => $data['name'],
			'nickname' => $data['nickname'],
			'email' => $data['email'],
			'password' => bcrypt($data['password']),
			'birth_date'=> Carbon::createFromFormat('Y-m-d H:i:s', $data['birth_date']),
			'role_id' => $data['role_id']
		]);
	}

}