<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestController;
use App\Services\Teacher\GetTeacherService;
use App\Transformers\TeacherTransformer;
use App\Transformers\ApiItemSerializer;
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

	public function get(Request $request, $id)
	{
		try {
			$teacher = GetTeacherService::get($id);
			$fractal = new Fractal\Manager();
			$fractal->setSerializer(new ApiItemSerializer);
			$item = new Fractal\Resource\Item($teacher, new TeacherTransformer);
			$data = $fractal->createData($item)->toArray(); 
			return $data;
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}
	
		
}