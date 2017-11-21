<?php 

namespace App\Http\Controllers\Api;

use Log;
use Illuminate\Http\Request;
use App\Exceptions\ValidationException;
use App\Services\Coupon\CreateCouponService;
use League\Fractal;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;


class CounponController extends RestController 
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
			return $this->badRequest($createService->getValidator()->errors()->all());
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
		
	}
}
