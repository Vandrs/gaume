<?php 

namespace App\Services\User;
use Validator;
use App\Models\User;
use App\Exceptions\ValidationException;
use App\Services\Service;
use App\Services\Location\UpdateAddressService;

class UpdateUserProfileService extends Service
{

	public static function registerPolicies() {}

	public function update(User $user, array $data)
	{
		
	}

	public function getRules(User $user)
	{

	}

	public function getMessages()
	{

	}
}