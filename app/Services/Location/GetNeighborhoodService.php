<?php 

namespace App\Services\Location;

use App\Models\Neighborhood;
use App\Utils\StringUtil;

class GetNeighborhoodService 
{
	public static function getAllByStateUF($uf, $name = null)
	{
		$query = Neighborhood::query()
				     		 ->where('uf', StringUtil::toupper($uf));
		if ($name) {
			$query->where('name','LIKE', '%'.$name.'%');
		}				     
		return $query->orderBy('name','ASC')
				   	 ->get();
	}
}