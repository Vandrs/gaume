<?php 

namespace App\Services\Transaction;

use App\Models\Transaction;
use App\Models\User;

class GetTransactionService
{
	public static function getByReferenceId($id)
	{
		return Transaction::query()->where('transaction_reference_id','=',$id)->first();				
	}

	public static function getUserTransactions(User $user)
	{
		return Transaction::query()->where('user_id','=',$user->id)
								   ->orderBy('last_event','DESC')
								   ->paginate(10);
	}
}