<?php 

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use App\Models\UserCoupon;

class Coupon extends Model 
{
	protected $fillable = [
		'code', 'coins', 'valid_until', 'use_limit', 'used_times'
	];

	protected $dates = [
		'created_at', 'updated_at', 'valid_until'
	];

	public function userCoupons() 
	{
		return $this->hasMany(UserCoupon::class);
	}

}