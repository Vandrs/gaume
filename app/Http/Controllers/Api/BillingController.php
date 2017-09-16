<?php 

namespace App\Http\Controllers\Api;

use Log;
use Illuminate\Http\Request;
use League\Fractal;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Services\Billing\GetUserBillingService;
use App\Transformers\UserBillingTransformer;


class BillingController extends RestController 
{
	public function listBillingUsers(Request $request)
	{
		try {
			$userPaginator = GetUserBillingService::getByPeriod($request->all());
			$paginatorAdapter = new IlluminatePaginatorAdapter($userPaginator);
			$fractal = new Fractal\Manager();
			$items = new Fractal\Resource\Collection($userPaginator->getCollection(), new UserBillingTransformer());
			$items->setPaginator($paginatorAdapter);
			$data = $fractal->createData($items)->toArray();
			return $this->success($data);
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
		
	}
}