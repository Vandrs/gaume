<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestController;
use App\Transformers\TransactionTransformer;
use App\Transformers\ApiItemSerializer;
use App\Services\Transaction\GetTransactionService;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Illuminate\Http\Request;
use League\Fractal;
use Log;

class TransactionController extends RestController
{

	public function list(Request $request)
	{
		try {
			$paginator = GetTransactionService::getUserTransactions($request->user());
			$paginatorAdapter = new IlluminatePaginatorAdapter($paginator);
			$fractal = new Fractal\Manager();
			$items = new Fractal\Resource\Collection($paginator->getCollection(), new TransactionTransformer);
			$items->setPaginator($paginatorAdapter);
			$data = $fractal->createData($items)->toArray(); 
			return $data;
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}	
}