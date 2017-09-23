<?php 

namespace App\Services\User;

use App\Models\User;
use App\Enums\EnumStatus;
use App\Events\UserOnline;
use App\Events\UserOffline;

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