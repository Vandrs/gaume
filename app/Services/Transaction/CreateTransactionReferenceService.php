<?php 

namespace App\Services\Transaction;

use App\Models\MonzyPoint;
use App\Models\User;
use App\Models\TransactionReference;
use Carbon\Carbon;

class CreateTransactionReferenceService 
{
	public static function create(User $user, MonzyPoint $points)
	{
		$code = self::getCode($user->email, $points->id);
		return TransactionReference::create([
			'user_id' => $user->id,
			'monzy_point_id' => $points->id,
			'code' => $code
		]);
	}

	private static function getCode($email, $pointId)
	{
		$strNow = Carbon::now()->format('YmdHis');
		return bcrypt($strNow.$email.$pointId);
	}
}