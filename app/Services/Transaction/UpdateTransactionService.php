<?php 

namespace App\Services\Transaction;

use App\Models\Transaction;
use laravel\pagseguro\Transaction\Information\Information;

class UpdateTransactionService
{
	public static function update(Transaction $transaction, Information $information)
	{

		return $transaction->update([
			'status' 		 		   => $information->getStatus()->getCode(), 
			'type' 			 		   => $information->getType() , 
			'last_event' 	 		   => $information->getLastEventDate(), 
			'must_update' 	 		   => false
		]);
	}
}