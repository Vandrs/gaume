<?php 

namespace App\Services\Transaction;

use App\Models\Transaction;
use App\Models\TransactionReference;
use App\Models\User;
use App\Enums\EnumTransactionStatus;
use laravel\pagseguro\Transaction\Information\Information;

class CreateTransactionService
{
	public static function create(TransactionReference $reference, Information $information)
	{
		return Transaction::create([
			'transaction_id' 		   => $information->getCode(), 
			'monzy_point_id' 		   => $reference->monzyPoint->id, 
			'user_id' 		 		   => $reference->user->id,
			'transaction_reference_id' => $reference->id,
			'status' 		 		   => $information->getStatus()->getCode(), 
			'type' 			 		   => $information->getType() , 
			'last_event' 	 		   => $information->getLastEventDate(), 
			'must_update' 	 		   => false
		]);
	}
}