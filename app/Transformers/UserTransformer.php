<?php 

namespace App\Transformers;

use League\Fractal;
use App\Models\User;

class UserTransformer extends Fractal\TransformerAbstract
{
	public function transform(User $user)
	{
		return [
			'id' 	   => $user->id,
			'name' 	   => $user->name,
			'nickname' => $user->nickname,
			'email'    => $user->email,
			'status'   => $user->status, 
			'photo'    => $user->getPhotoProfileUrl(),
			'role' => [
				'id'   => $user->role->id,
				'name' => $user->role->role
			]
		];
	}
}