<?php 

namespace App\Services\Wallet;

use App\Models\User;
use App\Models\Wallet;
use Carbon\Carbon;
use Config;

class CreateWalletService
{
	public static function create(User $user)
	{
		$amount = self::getPromoAmount();
		return Wallet::create([
			'user_id' => $user->id,
			'amount'  => $amount,
		]);
	}

	private static function getPromoAmount()
	{
		$now = Carbon::now();
		$promoDateLimit = Config::get('app.promo_date_limit');
		$promoCoinsAmount = Config::get('app.promo_monzy_points');
		if ($promoDateLimit && $promoCoinsAmount) {
			$dateLimit = Carbon::createFromFormat('d/m/Y', $promoDateLimit);			
			$dateLimit->addDay();
			if ($dateLimit->greaterThanOrEqualTo($now)) {
				return $promoCoinsAmount;
			}
		}
		return 0;
	}
}