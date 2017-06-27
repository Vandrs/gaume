<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestController;
use Illuminate\Http\Request;
use App\Services\Location\GetStateService;
use App\Services\Location\GetCityService;
use App\Services\Location\GetNeighborhoodService;

class LocationController extends RestController
{

	public function getStates()
	{
		return GetStateService::getAll()->toArray();
	}

	public function getCitiesByStateUf(Request $request, $uf)
	{
		$cities = GetCityService::getAllByStateUF($uf, $request->q);
		return $this->success($cities->toArray());
	}

	public function getNeighborhoodsByStateUf(Request $request, $uf)
	{
		$neighborhoods = GetNeighborhoodService::getAllByStateUF($uf, $request->q);
		return $this->success($neighborhoods->toArray());
	}
}