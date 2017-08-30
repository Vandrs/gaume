<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\MonzyPoint;
use App\Services\MonzyPoint\GetMonzyPointService;

class WalletController extends Controller
{
    public function index()
    {
    	$monzyPoints = GetMonzyPointService::getAll();
        return view('app.wallet.index', ['monzyPoints' => $monzyPoints]);
    }

    public function makePaymentRequest($id)
    {
    	die($id);
    }

}
