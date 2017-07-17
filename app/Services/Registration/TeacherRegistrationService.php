<?php 

namespace App\Services\Registration;

use Validator;
use Config;
use App\Models\User;
use App\Models\PreRegistration;
use App\Services\Service;
use App\Enums\EnumUserStatus;
use App\Enums\EnumRole;
use App\Services\Location\CreateAddressService;
use App\Services\User\UserProfilePhotoService;
use App\Services\BankAccount\SaveBankAccountService;
use App\Services\TeacherGame\CreateTeacherGameService;
use App\Services\Registration\GetPreRegistrationService;
use App\Services\Registration\DeletePreRegistrationService;
use App\Exceptions\ValidationException;
use App\Utils\StringUtil;
use Carbon\Carbon;


class TeacherRegistrationService extends Service
{

	private $bankAccountService;
	private $addressService;
	private $photoUploadService;
	private $teacherGameService;

	public static function registerPolicies() {}

	public function registerUser (array $data, $profileImage = null) 
	{
		$data['role_id'] = EnumRole::TEACHER_ID;
		$this->validator = Validator::make($data, $this->getRegistrationRules(), $this->getRegistrationMessages());

		if ($this->validator->fails()) {
			throw new ValidationException();
		}
		
		try {

			$this->deletePreRegistrationByCode($data['code']);

			$data['status'] = EnumUserStatus::ACTIVE;
			$user = $this->createUser($data);

			$this->createAddress($user, $data);
			$this->createBankAccount($user, $data);
			$this->createTeacherGame($user, $data);
			
			if ($profileImage) {
				$this->photoUploadService = new UserProfilePhotoService();
				$this->photoUploadService->uploadPhoto($user, $profileImage);
			}

			return $user;
			
		} catch(ValidationException $e) {
				if ($this->addressService) {
					$this->validator->messages()->merge($this->addressService->getValidator()->messages());
				}
				if ($this->bankAccountService) {
					$this->validator->messages()->merge($this->bankAccountService->getValidator()->messages());	
				}
				if ($this->teacherGameService) {
					$this->validator->messages()->merge($this->teacherGameService->getValidator()->messages());	
				}
				if ($this->photoUploadService) {
					$this->validator->messages()->merge($photoUploadService->getValidator()->messages());	
				}
			throw $e;
		}
	}

	public function getRegistrationRules()
	{

		$now = Carbon::now();
		$now->subYears(Config::get('app.min_age_teacher'));

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
			'birth_date.date_before_or_equal' => __('validation.custom.min_age', ['age' => Config::get('app.min_age_teacher')]),
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

	private function createAddress(User $user, array $data)
	{
		$addressData = $this->getAddressData($data);
		$this->addressService = new CreateAddressService();
		return $this->addressService->create($user, $addressData);
	}

	private function createBankAccount(User $user, array $data)
	{
		$bankAccountData = $this->getBankAccountData($data);
		$this->bankAccountService = new SaveBankAccountService();
		return $this->bankAccountService->create($user, $bankAccountData);
	}

	private function createTeacherGame(User $user, array $data)
	{
		$teacherGameData = $this->getTeacherGameData($data);

		if (empty($teacherGameData)) {
			$msg = __('site.registration.generic_game_error');
			$this->validator->errors()->add('games', $msg);
			throw new ValidationException($msg);
		}

		$this->teacherGameService = new CreateTeacherGameService();
		try {
			foreach ($teacherGameData as $gameId => $data) {
				$this->teacherGameService->create($user, $gameId, $data);	
			}
		} catch (ValidationException $e) {
			$msg = __('site.registration.generic_game_error');
			$this->validator->errors()->add('games', $msg);
			throw $e;	
		}
	}

	private function deletePreRegistrationByCode($code) 
	{
		$preRegistration = GetPreRegistrationService::getByCode($code);
		$deleteService = new DeletePreRegistrationService();
		$deleteService->delete($preRegistration);
	}

	private function getAddressData(array $data)
	{
		$address = [
			'zipcode' 	   => isset($data['zipcode']) ? StringUtil::justNumbers($data['zipcode']) : null ,
			'state' 	   => isset($data['state']) ? $data['state'] : null,
			'city' 		   => isset($data['city']) ? $data['city'] : null,
			'neighborhood' => isset($data['neighborhood']) ? $data['neighborhood'] : null,
			'street' 	   => isset($data['street']) ? $data['street'] : null,
			'number' 	   => isset($data['number']) ? $data['number'] : null,
			'complement'   => isset($data['complement']) ? $data['complement'] : null
		];
		return $address;
	}

	private function getBankAccountData(array $data)
	{
		return [
			'bank' 	  => isset($data['bank']) ? $data['bank'] : null,
			'agency'  => isset($data['agency']) ? StringUtil::justNumbers($data['agency']) : null,
			'account' => isset($data['account']) ? StringUtil::justNumbers($data['account']) : null,
			'digit'   => isset($data['digit']) ? $data['digit'] : null,
		];
	}

	private function getTeacherGameData(array $data)
	{
		return isset($data['games']) ? $data['games'] : null;
	}
}