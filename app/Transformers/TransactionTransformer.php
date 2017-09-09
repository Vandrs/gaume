<?php 

namespace App\Transformers;

use League\Fractal;
use App\Models\Transaction;
use App\Enums\EnumTransactionStatus;
use Lang;

class TransactionTransformer extends Fractal\TransformerAbstract
{
	public function transform(Transaction $transaction)
	{
		return [
			'id' 		   => $transaction->id,
			'code' 		   => $transaction->transaction_id,
			'status' 	   => $transaction->status,
			'status_label' => EnumTransactionStatus::getLabel($transaction->status),
			'monzyPoint' => [
				'id' 		  => $transaction->monzyPoint->id,
				'description' => $transaction->monzyPoint->description()
			],
			'last_event'   => $transaction->last_event->format('d/m/Y H:i:s')
		];
	}
}