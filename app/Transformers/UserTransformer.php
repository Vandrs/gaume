<?php 

namespace App\Transformers;

use League\Fractal;
use App\Models\User;

class UserTransformer extends Fractal\TransformerAbstract
{
	public function transform(User $user)
	{
		return [
			'id' 	=> $user->id,
			'name' 	=> $user->name,
			'email' => $user->email,
			'photo' => $user->getPhotoProfileUrl(),
			'role' => [
				'id'   => $user->role->id,
				'name' => $user->role->role
			]
		];
	}
}