<?php 

namespace App\Services\Wallet;

use App\Models\MonzyPoint;
use App\Models\Wallet;

class AddWalletPointsService
{
	public static function add(Wallet $wallet, MonzyPoint $points)
	{
		$amount = $wallet->amount + $points->points;
		return $wallet->update(['amount' => $amount]);
	}
}