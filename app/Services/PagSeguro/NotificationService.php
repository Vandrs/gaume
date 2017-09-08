<?php 

namespace App\Services\PagSeguro;

use Log;
use App\Models\TransactionReference;
use App\Models\Transaction;
use App\Services\Transaction\CreateTransactionService;
use App\Services\Transaction\UpdateTransactionService;
use App\Services\Transaction\GetTransactionService;
use App\Services\Transaction\GetTransactionReferenceService;
use App\Services\User\GetUserService;
use laravel\pagseguro\Transaction\Information\Information;

class NotificationService 
{

	private static $instance;
	private $statusChanged;

	public function __construct()
	{
		$this->statusChanged = false;
	}

	public static function receive(Information $information)
	{
		try {
			$instance = self::getInstance();
			if ($information->getItemCount() == 1) {
				$reference = GetTransactionReferenceService::getByCode($information->getReference());
				$transaction = $instance->getTransaction($reference);
				if (empty($transaction)) {
					$transaction = $instance->createTransaction($reference, $information);
				} else {
					$instance->updateTransaction($transaction, $information);
				}
				if ($instance->hasStatusChanged()) {
					
				}
				return $transaction;
			} else {
				throw new \Exception("Transaction retornou mais de 1 item");
			}
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
		}
	}

	private function getTransaction(TransactionReference $reference)
	{
		return GetTransactionService::getByReferenceId($reference->id);
	}

	private function createTransaction(TransactionReference $reference, Information $information)
	{
		$this->statusChanged = true;
		return CreateTransactionService::create($reference, $information);
	}

	private function hasStatusChanged()
	{
		$this->statusChanged;
	}

	private function updateTransaction(Transaction $transaction, Information $information)
	{
		$oldStatus = $transaction->status;
		UpdateTransactionService::update($transaction, $information);
		$transaction->fresh();
		if ($oldStatus != $transaction->status) {
			$this->statusChanged = true;
		}
	}

	private static function getInstance()
	{
		if (empty(self::$instance)) {
			self::$instance = new self();
		}
		return self::$instance;
	}

}