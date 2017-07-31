<?php 

namespace App\Transformers;

use League\Fractal;
use App\Models\User;
use App\Models\Address;

class UserProfileTransformer extends Fractal\TransformerAbstract
{
	public function transform(User $user)
	{
		return [
			'id' 	 	 => $user->id,
			'cpf'    	 => $user->cpf,
			'name' 	 	 => $user->name,
			'nickname' 	 => $user->nickname,
			'email' 	 => $user->email,
			'photo_profile' => $user->getPhotoProfileUrl(),
			'birth_date' => $user->birth_date->__toString(),
			'created_at' => $user->created_at->__toString(),
			'role' => [
				'id'   => $user->role->id,
				'name' => $user->role->role
			],
			'address' => $this->getAddress($user),
			'bankAccount' => $this->getBankAccount($user),
		];
	}

	private function getAddress(User $user)
	{
		$address = $user->address;
		if ($address) {
			return [
				'zipcode' => $address->zipcode,
				'neighborhood' => $address->neighborhood,
				'city' 	 => $address->city,
				'state'  => $address->state,
				'street' => $address->street,
				'number' => $address->number,
				"complement" => $address->complement,
			];
		}
		return null;
	}

	private function getBankAccount(User $user)
	{
		if ($user->bankAccount) {
			return [
				'bank'    => $user->bankAccount->bank,
				'agency'  => $user->bankAccount->agency,
				'account' => $user->bankAccount->account,
				'digit'   =>  $user->bankAccount->digit
			];
		} 
		return null;
	}
}