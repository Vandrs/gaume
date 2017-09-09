<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Http\Controllers\Controller;
use App\Models\MonzyPoint;
use App\Services\MonzyPoint\GetMonzyPointService;
use App\Enums\EnumRole;
use App\Services\PagSeguro\CheckoutService;
use Log;
use Lang;

class WalletController extends Controller
{
    public function index(Request $request)
    {
    	$monzyPoints = GetMonzyPointService::getAll();
        if ($request->transaction_id) {
            session()->flash('errors', [Lang::get('wallet.buy_success')]);
            session()->flash('error_class', 'alert-success');
        }
        return view('app.wallet.index', ['monzyPoints' => $monzyPoints, 'wallet' => $request->user()->wallet]);
    }

    public function makePaymentRequest(Request $request, $id)
    {
    	$user = $request->user();
    	try {
    		if (!$user->hasRole(EnumRole::STUDENT)) {
    			session()->flash('errors',[__('validation.custom.unauthorized')]);
    			return back();
    		}
    		$monzyPoint = MonzyPoint::findOrFail($id);
			$checkoutService = new CheckoutService();
			$information = $checkoutService->makeCheckout($user, $monzyPoint);
			if (empty($information)) {
				session()->flash('errors',[__('validation.custom.unexpected')]);
    			return back();
			}
			return redirect($information->getLink());
    	} catch (ModelNotFoundException $e) {
    		session()->flash('errors',[__('wallet.plan_not_found')]);
    		return back();
    	} catch (\Exception $e) {
    		Log::error($e->getMessage());
    		Log::error($e->getTraceAsString());
    		session()->flash('errors',[__('validation.custom.unexpected')]);
    		return back();
    	}
    }

}
