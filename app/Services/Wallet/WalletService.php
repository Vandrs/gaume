<?php 

namespace App\Services\Wallet;

use App\Models\MonzyPoint;
use App\Models\Wallet;

class WalletService
{
	public static function addPoints(Wallet $wallet, MonzyPoint $points)
	{
		$amount = $wallet->amount + $points->points + $points->bonus;
		return $wallet->update(['amount' => $amount]);
	}

	public static function checkPoints(Wallet $wallet, $points)
	{
		return $wallet->amount >= $points;
	}

	public static function removePoints(Wallet $wallet, $points)
	{
		$amount = $wallet->amount - $points;
		return $wallet->update(['amount' => $amount]);	
	}

	public static function returnPoints(Wallet $wallet, $points)
	{
		$amount = $wallet->amount + $points;
		return $wallet->update(['amount' => $amount]);	
	}
}