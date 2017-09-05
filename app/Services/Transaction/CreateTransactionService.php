<?php 

namespace App\Services\Transaction;

use App\Models\Transaction;
use App\Models\MonzyPoint;
use App\Enums\EnumTransactionStatus;
use laravel\pagseguro\Transaction\Information\Information;

class CreateTransactionService
{
	public static function create(MonzyPoint $monzyPoint, Information $information)
	{
		return Transaction::create([
			'transaction_id' => $information->getCode(), 
			'monzy_point_id' => $monzyPoint->id, 
			'status' 		 => false, 
			'type' 			 => $information->getType() , 
			'last_event' 	 => $information->getLastEventDate(), 
			'must_update' 	 => true
		]);
	}
}