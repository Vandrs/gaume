<?php 

namespace App\Http\Controllers\Api;

use Log;
use League\Fractal;
use Illuminate\Http\Request;
use App\Http\Controllers\Api\RestController;
use App\Services\User\GetUserAdminService;
use App\Services\User\GetUserService;
use App\Services\User\ActivateInactivateUserService;
use App\Transformers\UserTransformer;
use App\Transformers\ApiItemSerializer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class UserAdminController extends RestController
{
	public function list(Request $request)
	{
		try {
			$usersPaginator = GetUserAdminService::getAll($request->all());
			$fractal = new Fractal\Manager();
			$items = new Fractal\Resource\Collection($usersPaginator->getCollection(), new UserTransformer());
			$paginatorAdapter = new IlluminatePaginatorAdapter($usersPaginator);
			$items->setPaginator($paginatorAdapter);
			$data = $fractal->createData($items)->toArray();
			return $this->success($data);
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function activate(Request $request, $id)
	{
		try {
			$user = GetUserService::get($id);
			ActivateInactivateUserService::activate($user);
			return $this->success();
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function inactivate(Request $request, $id)
	{
		try {
			$user = GetUserService::get($id);
			ActivateInactivateUserService::inactivate($user);
			return $this->success();
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}
}

