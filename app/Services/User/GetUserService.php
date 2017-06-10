<?php 

namespace App\Services\User;
use App\Models\User;

class GetUserService
{
	public static function get($id)
	{
		return User::with(['role','address'])
				   ->where('id',$id)
				   ->first();
	}
}