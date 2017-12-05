<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Api\RestController;
use App\Transformers\ApiItemSerializer;
use App\Transformers\WalletTransformer;
use App\Exceptions\ValidationException;
use App\Services\Coupon\AddCouponService;
use League\Fractal;
use Log;
use DB;

class WalletController extends RestController
{
	public function get(Request $request) 
	{
		try {
			$wallet = $request->user()->wallet;
			$fractal = new Fractal\Manager();
			$fractal->setSerializer(new ApiItemSerializer);
			$item = new Fractal\Resource\Item($wallet, new WalletTransformer);
			$data = $fractal->createData($item)->toArray(); 
			return $this->success($data);
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function addCoupon(Request $request) 
	{
		try {
			DB::beginTransaction();
			$service = new AddCouponService();
			$userCoupon = $service->add($request->user(), $request->code);
			DB::commit();
			return $this->created($userCoupon->id);
		} catch (ValidationException $e) {
			DB::rollback();
			$errors = $service->getValidator()->errors()->jsonSerialize();
			Log::error($e->getMessage().': '.json_encode($errors));
			return $this->badRequest($errors);
		} catch (ModelNotFoundException $e) {
			DB::rollback();
			Log::error($e->getMessage());
			Log::debug($e->getTraceAsString());
			return $this->notfound();
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			Log::debug($e->getTraceAsString());
			return $this->internalError();
		}
	}
}