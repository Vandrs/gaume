<?php 

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Carbon\Carbon;


class BillingController extends Controller
{
	public function index()
	{
		$dtEnd = Carbon::now();
		$dtIni = Carbon::now();
		$dtIni->firstOfMonth();
		return view('app.billing.index',[
			'dt_ini' => $dtIni, 
			'dt_end' => $dtEnd
		]);
	}
}