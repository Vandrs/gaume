<?php 

namespace App\Services;

abstract class Service 
{
	protected $validator;

	abstract static function registerPolicies();

	public function getValidator()
	{
		return $this->validator;
	}
}