<?php 

namespace App\Services\User;

use App\Models\User;
use App\Enums\EnumStatus;

class UserOnlineService
{
	public static function setOnline(User $user)
	{
		return $user->update(['is_online' => EnumStatus::ACTIVE]);
	}

	public static function setOffline(User $user)
	{
		return $user->update(['is_online' => EnumStatus::INACTIVE]);
	}
}