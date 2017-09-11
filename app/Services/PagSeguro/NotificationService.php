<?php 

namespace App\Services\PagSeguro;

use Log;
use App\Models\TransactionReference;
use App\Models\Transaction;
use App\Services\Transaction\CreateTransactionService;
use App\Services\Transaction\UpdateTransactionService;
use App\Services\Transaction\GetTransactionService;
use App\Services\Transaction\GetTransactionReferenceService;
use App\Services\Wallet\WalletService;
use App\Services\User\GetUserService;
use App\Enums\EnumTransactionStatus;
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
				if ($instance->hasStatusChanged() && $transaction->status == EnumTransactionStatus::PAID) {
					WalletService::addPoints($transaction->user->wallet, $transaction->monzyPoint);
				}
				return $transaction;
			} else {
				throw new \Exception("Transaction retornou mais de 1 item");
			}
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			throw $e;
		}
	}

	public function getTransaction(TransactionReference $reference)
	{
		return GetTransactionService::getByReferenceId($reference->id);
	}

	public function createTransaction(TransactionReference $reference, Information $information)
	{
		$this->statusChanged = true;
		return CreateTransactionService::create($reference, $information);
	}

	public function hasStatusChanged()
	{
		return $this->statusChanged;
	}

	public function updateTransaction(Transaction $transaction, Information $information)
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