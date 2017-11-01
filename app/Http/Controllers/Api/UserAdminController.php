<?php 

namespace App\Http\Controllers\Api;

use Log;
use League\Fractal;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\RestController;
use App\Services\User\GetUserAdminService;
use App\Transformers\UserTransformer;
use App\Transformers\ApiItemSerializer;

class UserAdminController extends RestController
{
	public function list(Request $request)
	{
		try {
			$users = GetUserAdminService::getAll($request->all());
			

		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}
}

