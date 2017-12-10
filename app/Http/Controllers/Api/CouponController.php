<?php 

namespace App\Http\Controllers\Api;

use Log;
use Illuminate\Http\Request;
use App\Exceptions\ValidationException;
use App\Services\Coupon\CreateCouponService;
use App\Services\Coupon\GetCouponService;
use App\Transformers\CouponTransformer;
use League\Fractal;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;


class CouponController extends RestController 
{
	public function create(Request $request)
	{
		try {
			$createService = new CreateCouponService();
			$coupon = $createService->create($request->all());
			return $this->created($coupon->id);
		} catch (ValidationException $e) {
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			$errors = $createService->getValidator()->errors()->jsonSerialize();
			return $this->badRequest($errors);
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
		
	}

	public function getAll(Request $reques)
	{
		try {
			$getService = new GetCouponService();
			$paginator = $getService->getAll($reques->all());
			$paginatorAdapter = new IlluminatePaginatorAdapter($paginator);
			$fractal = new Fractal\Manager();
			$items = new Fractal\Resource\Collection($paginator->getCollection(), new CouponTransformer());
			$items->setPaginator($paginatorAdapter);
			$data = $fractal->createData($items)->toArray(); 
			return $this->success($data);
		} catch (ValidationException $e) {
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			$errors = $getService->getValidator()->errors()->jsonSerialize();
			return $this->badRequest($errors);
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}
}
