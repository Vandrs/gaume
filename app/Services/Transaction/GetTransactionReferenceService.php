<?php 

namespace App\Services\Transaction;

use App\Models\TransactionReference;

class GetTransactionReferenceService 
{
	public static function getByCode($code)
	{
		return TransactionReference::where('code', '=', $code)
								   ->firstOrFail();
	}
}