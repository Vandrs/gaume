<?php 

namespace App\Services\Coupon;

use Validator;
use App\Models\Coupon;
use Carbon\Carbon;
use App\Exceptions\ValidationException;

class CreateCouponService
{

	private $validator;

	public function create(array $data)
	{
		$this->validator = Validator::make($data, $this->validation(), $this->messages());
		if ($this->validator->fails()) {
			throw new ValidationException('Parâmetros inválidos');
		}
		$validUntil = (isset($data['valid_until']) && !empty($data['valid_until'])) ? Carbon::createFromFormat('Y-m-d', $data['valid_until']) : null;
		$coupon = Coupon::create([
			'code' 		  => $data['code'],
			'coins' 	  => $data['coins'],
			'use_limit'   => $data['use_limit'],
			'valid_until' => $validUntil
		]);
		return $coupon; 
	}

	public function validation()
	{
		$date = Caborn::now();
		$date->addDay();
		$strDate = $date->format('Y-m-d H:i:s');
		return [
			'code' 		  => 'required|unique|max:255',
			'coins'		  => 'required|integer',
			'use_limit'   => 'required|integer',
			'valid_until' => 'date|dateAfterOrEqual:'.$strDate
		];
	}

	public function messages()
	{
		return [
			'code.required' 		 	  => __('validation.required', 				  ['attribute' => __('coupon.code')]),
			'code.unique' 		 		  => __('validation.unique', 				  ['attribute' => __('coupon.code')]),
			'code.max' 		 		  	  => __('validation.max.numeric', 			  ['attribute' => __('coupon.code'), 'max' => '255']),
			'coins.required'  		      => __('validation.required', 				  ['attribute' => __('coupon.code')]),
			'coins.integer'  		      => __('valication.integer', 				  ['attribute' => __('wallet.coins')]),
			'use_limit.required'  		  => __('validation.required', 				  ['attribute' => __('coupon.use_limit')]),
			'use_limit.integer'  		  => __('validation.integer', 				  ['attribute' => __('coupon.use_limit')]),
			'valid_unil.date' 			  => __('validation.date', 				      ['attribute' => __('coupon.valid_until')]),
			'valid_unil.dateAfterOrEqual' => __('validation.custom.dateAfterOrEqual', ['attribute' => __('coupon.valid_until')]), 
		];
	}

	public function getValidator()
	{
		return $this->validator;
	}
}
