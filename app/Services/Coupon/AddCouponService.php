<?php 

namespace App\Services\Coupon;

use App\Models\User;
use App\Models\Waller;
use App\Models\UserCoupon;
use App\Models\Coupon;
use App\Exceptions\ValidationException;
use App\Services\Coupon\GetCouponService;
use App\Services\Wallet\WalletService;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Carbon\Carbon;
use Validator;

class AddCouponService 
{

	private $validator;

	public function add(User $user, $code) 
	{
		$this->validator = Validator::make([],[],[]);
		$coupon = $this->getCoupon($code);
		$this->validateExpired($coupon);
		$this->validateUse($coupon, $user);

		$userCoupon = UserCoupon::create([
			'user_id'   => $user->id,
			'coupon_id' => $coupon->id
		]);
		
		$coupon->used_times += 1;
		$coupon->save();

		WalletService::returnPoints($user->wallet, $coupon->coins);

		return $userCoupon;
	}

	private function getCoupon($code) 
	{
		try {
			return GetCouponService::getByCode($code);
		} catch (ModelNotFoundException $e) {
			$this->validator->errors()->add('code',__('coupon.not_found'));
			throw new ValidationException(__('coupon.not_found'));
		}
	}

	private function validateExpired(Coupon $coupon)
	{
		$now = Carbon::now();
		$now->addDay();
		if ($coupon->valid_until) {
			$validUntil = Carbon::createFromTimestamp($coupon->valid_until->getTimestamp());
			if ($now->greaterThan($validUntil)) {
				$this->validator->errors()->add('code',__('coupon.expired'));
				throw new ValidationException(__('coupon.expired'));		
			}
		}
	}

	private function validateUseLimit(Coupon $coupon)
	{
		if ($coupon->user_limit >= $coupon->used_times) {
			$this->validator->errors()->add('code',__('coupon.use_limite'));
			throw new ValidationException(__('coupon.use_limite'));		
		}
	}

	private function validateUse(Coupon $coupon, User $user) 
	{
		$exists = UserCoupon::query()
				  			->where('user_id','=', $user->id)
				  			->where('coupon_id','=', $coupon->id)
				  			->exists();
		if ($exists) {
			$this->validator->errors()->add('code',__('coupon.already_used'));
			throw new ValidationException(__('coupon.already_used'));	
		}
	}

	public function getValidator()
	{
		return $this->validator;
	}


}