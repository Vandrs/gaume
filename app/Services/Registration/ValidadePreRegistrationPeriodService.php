<?php

namespace App\Services\Registration;

use App\Models\PreRegistration;
use App\Exceptions\ValidationException;
use App\Utils\Util;
use Carbon\Carbon;
use Config;
use Lang;

class ValidadeRegistrationPeriodService
{
	public function validate(PreRegistration $preRegistration) 
	{
		$dateCheck = Util::coalesce(
						$preRegistration->mailed_at,
						$preRegistration->updated_at, 
						$preRegistration->created_at
					);
		$period = Config::get('pre_registration.registration_period');	
		$dateCheck->addDays($period);
		if (!$dateCheck->greaterThanOrEqualTo(Carbon::now())) {
			$msg = Lang::get('pre_registration.invalid_period');
			throw new ValidationException($msg);
		}
		return true;
	}
}