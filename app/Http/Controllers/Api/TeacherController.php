<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestController;
use App\Services\Teacher\GetTeacherService;
use App\Transformers\TeacherTransformer;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use Illuminate\Http\Request;
use League\Fractal;
use Log;

class TeacherController extends RestController
{

	public function list(Request $request)
	{
		try {
			$teachersPaginator = GetTeacherService::getForClass($request->all());
			$paginatorAdapter = new IlluminatePaginatorAdapter($teachersPaginator);
			$fractal = new Fractal\Manager();
			$items = new Fractal\Resource\Collection($teachersPaginator->getCollection(), new TeacherTransformer);
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