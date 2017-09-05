<?php 

namespace App\Services\PagSeguro;

use Log;
use App\Models\MonzyPoint;
use App\Services\Transaction\CreateTransactionService;
use laravel\pagseguro\Transaction\Information\Information;

class NotificationService 
{

	private static $instance;

	public static function receive(Information $information)
	{
		try {
			$instance = self::getInstance();
			if ($information->getItemCount() == 1) {
				$monzyPoint = $instance->getMonzyPointProduct($information);
				if ($monzyPoint) {
					CreateTransactionService::create($monzyPoint, $information);		
				}
			} else {
				throw new \Exception("Transaction retornou mais de 1 item");
			}
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
		}
	}

	private function getMonzyPointProduct(Information $information)
	{
		$item = $information->getItems()->offsetGet(0);
		if (empty($item)) {
			Log::info('Transaction: '. $information->getCode(). ' Nenhum item encontrado');
			return;
		}
		return MonzyPoint::findOrFail($item->getId());
	}

	private static function getInstance()
	{
		if (empty(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}
}