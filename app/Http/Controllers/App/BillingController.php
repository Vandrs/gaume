<?php 

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;

class BillingController extends Controller
{
	public function index()
	{
		return view('app.billing.index');
	}
}