<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

class CouponController extends Controller
{
	public function index()
	{
		return view('app.coupon.index');
	}
}