<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestController;
use App\Services\Platform\GetAllPlatformService;
use Log;

class PlatformController extends RestController
{
	public function getAll()
	{
		try {
			$platforms = GetAllPlatformService::getAll();	
			return $this->success($platforms->toArray());
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}
}