<?php 

namespace App\Services\Transaction;

use App\Models\Transaction;

class GetTransactionService
{
	public static function getByReferenceId($id)
	{
		return Transaction::query()->where('transaction_reference_id','=',$id)->first();				
	}
}