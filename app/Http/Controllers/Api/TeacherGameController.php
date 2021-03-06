<?php 

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Api\RestController;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use App\Transformers\ApiItemSerializer;
use	App\Transformers\TeacherGameTransformer;
use	App\Transformers\TeacherGameLessonTransformer;
use App\Services\TeacherGame\GetTeacherGameService;
use App\Services\TeacherGame\UpdateTeacherGameService;
use App\Models\User;
use Log;
use DB;
use League\Fractal;

class TeacherGameController extends RestController
{

	public function get(Request $request)
	{
		try {
			$teacherGames = GetTeacherGameService::getAll($request->user());
			$items = new Fractal\Resource\Collection($teacherGames->all(), new TeacherGameTransformer);
			$fractal = new Fractal\Manager();
			$fractal->setSerializer(new ApiItemSerializer);
			$data = $fractal->createData($items)->toArray(); 
			return $this->success($data);
		} catch (ModelNotFoundException $e) {
			return $this->notFound();
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function getByTeacherId(Request $request, $id)
	{
		try {
			$teacher = User::findOrFail($id);
			$teacherGames = GetTeacherGameService::getAll($teacher);
			$items = new Fractal\Resource\Collection($teacherGames->all(), new TeacherGameTransformer);
			$fractal = new Fractal\Manager();
			$fractal->setSerializer(new ApiItemSerializer);
			$data = $fractal->createData($items)->toArray(); 
			return $this->success($data);
		} catch (ModelNotFoundException $e) {
			return $this->notFound();
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function getGamesForLesson(Request $request, $id)
	{
		try {
			$user = User::findOrFail($id);
			$teacherGames = GetTeacherGameService::getAll($user);
			$items = new Fractal\Resource\Collection($teacherGames->all(), new TeacherGameLessonTransformer);
			$fractal = new Fractal\Manager();
			$fractal->setSerializer(new ApiItemSerializer);
			$data = $fractal->createData($items)->toArray(); 
			return $this->success($data);
		} catch (ModelNotFoundException $e) {
			return $this->notFound();
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function update(Request $request)
	{
		try {
			DB::beginTransaction();
			$service = new UpdateTeacherGameService();
			$service->update($request->user(), $request->all());
			DB::commit();
			return $this->success();
		} catch (ValidationException $e) {
			DB::rollback();
			$errors = $service->getValidator()->errors()->jsonSerialize();
			Log::error($e->getMessage().': '.json_encode($errors));
			return $this->badRequest($errors);
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}
}