<?php 

namespace App\Transformers;

use League\Fractal;
use App\Models\Coupon;


class CouponTransformer extends Fractal\TransformerAbstract
{
	public function transform(Coupon $coupon)
	{
		return [
			'id'    			   => $coupon->id,
			'code'  			   => $coupon->code, 
			'coins' 			   => $coupon->coins, 
			'valid_until' 		   => $coupon->valid_until->__toString(), 
			'formated_valid_until' => $coupon->valid_until->format('d/m/Y'), 
			'use_limit'  		   => $coupon->use_limit, 
			'used_times' 		   => $coupon->used_times,
			'created_at' 		   => $coupon->created_at->__toString()
		];
	}
}
