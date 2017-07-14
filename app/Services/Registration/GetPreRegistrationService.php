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

	public function getAll(array $parameters)
	{
		$query = PreRegistration::query()->orderBy('name','ASC');
		
		if (isset($parameters['name']) && !empty($parameters['name'])) {
			$query->where('name','LIKE','%'.$parameters['name'].'%');
		}

		if (isset($parameters['email']) && !empty($parameters['email'])) {
			$query->where('email','LIKE','%'.$parameters['email'].'%');
		}

		$paginator = $query->paginate(20);
		$queryParams = array_diff_key($parameters, array_flip(['page']));
		$paginator->appends($queryParams);
		return $paginator;
	}
}