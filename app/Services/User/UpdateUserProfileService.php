<?php 

namespace App\Services\User;
use Validator;
use App\Models\User;
use App\Exceptions\ValidationException;
use App\Services\Service;
use App\Services\Location\UpdateAddressService;
use App\Services\BankAccount\SaveBankAccountService;
use Illuminate\Validation\Rule;

class UpdateUserProfileService extends Service
{

	public static function registerPolicies() {}

	public function update(User $user, array $data)
	{
		$this->validator = Validator::make($data, $this->getRules($user), $this->getMessages());
		if ($this->validator->fails()) {
			throw new ValidationException('Parâmetros inválidos!');
		}
		
		$user->update([
			'nickname' => $data['nickname'],
			'email'    => $data['email']
		]);

		try {
			$addressData = isset($data['address']) ? $data['address'] : null;
			$addresService = new UpdateAddressService();
			$addresService->update($user->address, $addressData);


			if ($user->bankAccount) {
				$bankAccountService = new SaveBankAccountService();
				$bankData = isset($data['bankAccount']) ? $data['bankAccount'] : [];
				$bankAccountService->update($user->bankAccount, $bankData);
			}

		} catch (ValidationException $e) {
			$this->validator->messages()->merge($addresService->getValidator()->messages());	
			if (isset($bankAccountService)) {
				$this->validator->messages()->merge($bankAccountService->getValidator()->messages());	
			}
			throw $e;
		}
	}

	public function getRules(User $user)
	{
		return [
			'nickname' => [
				'required',
				'max:20',
				Rule::unique('users')->ignore($user->id)
			],
			'email' => [
				'required',
				'max:255',
				'email',
				Rule::unique('users')->ignore($user->id)	
			]
		];
	}

	public function getMessages()
	{
		return [
			'nickname.required' 		      => __('validation.required', ['attribute' => __('site.registration.nickname')]),
			'nickname.max' 				      => __('validation.max.string', ['attribute' => __('site.registration.nickname'), 'max' => 20]),
			'nickname.unique' 			      => __('validation.unique', ['attribute' => __('site.registration.nickname')]),
			'email.required' 			      => __('validation.required', ['attribute' => __('site.registration.email')]),
			'email.email'	 			      => __('validation.email', ['attribute' => __('site.registration.email')]),
			'email.max'		 			      => __('validation.max.string', ['attribute' => __('site.registration.email'), 'max' => 255]),
			'email.unique'	 			      => __('validation.unique', ['attribute' => __('site.registration.email')]),
		];
	}

}