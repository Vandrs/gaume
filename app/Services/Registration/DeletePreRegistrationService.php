<?php

namespace App\Services\Registration;

use App\Models\PreRegistration;

class DeletePreRegistrationService
{
	public function delete(PreRegistration $preRegistration)
	{
		$preRegistration->preRegistrationPlatforms->each(function($preRegistrationPlatform){
			$preRegistrationPlatform->delete();
		});
		$preRegistration->delete();
	}
}