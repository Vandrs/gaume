<?php

namespace App\Services\Registration;

use App\Services\Service;
use App\Models\PreRegistration;


class GetPreRegistrationService extends Service
{

	public static function registerPolicies() {}

	public function get($id)
	{
		return PreRegistration::with(['preRegistrationPlatforms'])
							  ->where('id', $id)
							  ->firstOrFail();
	}
}