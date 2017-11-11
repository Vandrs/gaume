<?php 

namespace App\Services\User;

use App\Models\User;
use App\Enums\EnumActiveInactive;

class ActivateInactivateUserService
{
	public static function activate(User $user) 
	{
		$user->update(['status' => EnumActiveInactive::ACTIVE]);
	}

	public static function inactivate(User $user) 
	{
		$user->update(['status' => EnumActiveInactive::INACTIVE]);
	}	
}