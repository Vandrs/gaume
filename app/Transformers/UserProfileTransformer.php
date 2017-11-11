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
			'address'	  => $this->getAddress($user),
			'bankAccount' => $this->getBankAccount($user),
			'media' 	  => $this->getMedia($user),
			'evaluation'  => $this->parseEvaluation($user)
		];
	}

	private function getAddress(User $user)
	{
		$address = $user->address;
		if ($address) {
			return [
				'zipcode' 	   => $address->zipcode,
				'neighborhood' => $address->neighborhood,
				'city' 	 	   => $address->city,
				'state'  	   => $address->state,
				'street' 	   => $address->street,
				'number' 	   => $address->number,
				'complement'   => $address->complement,

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

	private function getMedia(User $user)
	{
		if ($user->media) {
			return [
				'id' => $user->media->id,
				'media' => $user->media->media,
				'nickname' => $user->media->nickname
			];
		}
		return new \StdClass;
	}

	public function parseEvaluation(User $user)
	{
		if ($user->evaluation) {
			return [
				'id' 			  => $user->evaluation->id,
				'note' 			  => $user->evaluation->note,
				'qtd_evaluations' => $user->evaluation->qtd_evaluations
			];
		} else {
			return [
				'id' 			  => null,
				'note' 			  => 0,
				'qtd_evaluations' => 0
			]; 
		}
	}
}