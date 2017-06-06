<?php 

namespace App\Services\Location;

use App\Models\City;
use App\Utils\StringUtil;

class GetCityService 
{
	public static function getAllByStateUF($uf, $name = null)
	{
		$query = City::query()
				     ->where('uf', StringUtil::toupper($uf));
		if ($name) {
			$query->where('name','LIKE', '%'.$name.'%');
		}				     
		return $query->orderBy('name','ASC')
				   	 ->get();
	}
}