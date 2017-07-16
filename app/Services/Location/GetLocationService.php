<?php

namespace App\Services\Location;

use App\Utils\StringUtil;
Use App\Models\Location;

class GetLocationService
{
	public static function getLocationByCEP($cep) 
	{
		$cep = StringUtil::justNumbers($cep);
		return Location::query()
					   ->where('cep_inicial','<=',$cep)
					   ->where('cep_final','>=',$cep)		
					   ->firstOrFail();

	}
}