<?php 

namespace App\Services\Wallet;

use App\Models\User;
use App\Models\Wallet;

class CreateWalletService
{
	public static function create(User $user)
	{
		return Wallet::create([
			'user_id' => $user->id,
			'amount'  => 0,
		]);
	}
}