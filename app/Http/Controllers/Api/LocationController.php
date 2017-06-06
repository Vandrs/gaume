<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestController;
use Illuminate\Http\Request;
use App\Services\Location\GetCityService;

class LocationController extends RestController
{
	public function getCitiesByStateUf(Request $request, $uf)
	{
		$cities = GetCityService::getAllByStateUF($uf, $request->q);
		return $this->success($cities->toArray());
	}
}