<?php 

namespace App\Services\BankAccount;

use App\Services\Service;
use App\Exceptions\ValidationException;
use App\Models\User;
use App\Models\BankAccount;
use App\Util\StringUtil;
use Validator;


class SaveBankAccountService extends Service
{

	public static function registerPolicies() {}

	public function create(User $user, array $data)
	{
		$this->validator = Validator::make($data, $this->getValidation(), $this->getMessages());
		if ($this->validator->fails()) {
			throw new ValidationException('Dados bancários inválidos');
		}

		return BankAccount::create([
			'user_id' => $user->id,
			'bank' => $data['bank'], 
			'agency' => $data['agency'], 
			'account' => $data['account'], 
			'digit' => $data['digit']
		]);
	}

	public function update(BankAccount $bankAccount, array $data)
	{
		$this->validator = Validator::make($data, $this->getValidation(), $this->getMessages());
		if ($this->validator->fails()) {
			throw new ValidationException('Dados bancários inválidos');
		}
		return $bankAccount->update([
			'bank' => $data['bank'], 
			'agency' => $data['agency'], 
			'account' => $data['account'], 
			'digit' => $data['digit']
		]);
	}

	public function getValidation()
	{
		return [
			'bank'    => 'required|max:100',
			'agency'  => 'required|digits_between:4,20|numeric',
			'account' => 'required|digits_between:2,20|numeric',
			'digit'   => 'required|digits_between:1,2|numeric'
		];
	}

	public function getMessages()
	{
		return [
			'bank.required'    => __('validation.required', ['attribute' => __('site.registration.bank')]),
			'bank.max' 		   => __('validation.max.string', ['attribute' => __('site.registration.bank'), 'max' => 100]) ,
			'agency.required'  => __('validation.required', ['attribute' => __('site.registration.agency')]),
			'agency.max' 	   => __('validation.max.string', ['attribute' => __('site.registration.agency'), 'max' => 20, 'min' => 4]),
			'agency.numeric'   => __('validation.numeric', ['attribute' => __('site.registration.agency')]),
			'account.required' => __('validation.required', ['attribute' => __('site.registration.account')]),
			'account.max'	   => __('validation.max.string', ['attribute' => __('site.registration.account'), 'max' => 20, 'min' => 2]),
			'account.numeric'  => __('validation.numeric', ['attribute' => __('site.registration.account')]),
			'digit.required'   => __('validation.required', ['attribute' => __('site.registration.digit')]), 
			'digit.max'		   => __('validation.max.string', ['attribute' => __('site.registration.digit'), 'max' => 2, 'min' => 1]),
			'digit.numeric'    => __('validation.numeric', ['attribute' => __('site.registration.digit')])
		];
	}
}