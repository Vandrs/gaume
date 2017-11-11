<?php 

namespace App\Services\User;
use App\Models\User;

class GetUserService
{
	public static function get($id)
	{
		return User::with(['role','address'])
				   ->where('id',$id)
				   ->firstOrFail();
	}

	public static function getByEmail($email)
	{
		return User::where('email','=', $email)
				   ->firstOrFail();
	}	
}