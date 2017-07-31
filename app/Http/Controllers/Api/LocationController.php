<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestController;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Services\Location\GetLocationService;
use App\Transformers\ApiItemSerializer;
use App\Transformers\LocationTransformer;
use Log;
use League\Fractal;

class LocationController extends RestController
{

	public function getAddressByCep(Request $request, $cep)
	{
		try {
			$location = GetLocationService::getLocationByCEP($cep);
			$fractal = new Fractal\Manager();
			$fractal->setSerializer(new ApiItemSerializer);
			$item = new Fractal\Resource\Item($location, new LocationTransformer);
			$data = $fractal->createData($item)->toArray(); 
			return $this->success($data);
		} catch (ModelNotFoundException $e) {
			return $this->notFound();
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}
}