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
			'address' => $this->getAddress($user)
		];
	}

	private function getAddress(User $user)
	{
		$address = $user->address;
		if ($address) {
			return [
				'neighborhood' => $this->getNeighborhood($address),
				'city' 	 => $this->getCity($address),
				'state'  => $this->getState($address),
				'street' => $address->street,
				'number' => $address->number,
				"complement" => $address->complement,
			];
		}
		return [];
	}

	private function getState(Address $address)
	{
		if ($address->state) {
			return [
				'id'   => $address->state->id,
				'name' => $address->state->name,
				'uf'   => $address->state->uf,
			];
		} 
		return [];
	}

	private function getCity(Address $address)
	{
		if ($address->city) {
			return [
				'id'   => $address->city->id,
				'name' => $address->city->name,
				'uf'   => $address->city->uf,
			];
		} 
		return [];	
	}

	private function getNeighborhood(Address $address)
	{
		if ($address->neighborhood) {
			return [
				'id'   => $address->neighborhood->id,
				'name' => $address->neighborhood->name,
				'uf'   => $address->neighborhood->uf,
			];
		} 
		return [];		
	}
}