<?php 

namespace App\Transformers;

use App\Models\Wallet;
use League\Fractal;


class WalletTransformer extends Fractal\TransformerAbstract
{
	public function transform(Wallet $wallet)
	{
		return [
			'id' 		 => $wallet->id,
			'amount'     => $wallet->amount,
			'user_id'    => $wallet->user_id,
			'created_at' => $wallet->created_at->__toString()
		];
	}
}