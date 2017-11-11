<?php 

namespace App\Http\Controllers\Api;

use Log;
use DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\Fractal;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;
use App\Http\Controllers\Api\RestController;
use App\Exceptions\AuthorizationException;
use App\Exceptions\ValidationException;
use App\Services\Lesson\GetLessonEvaluationService;
use App\Services\Lesson\UpdateLessonEvaluationService;
use App\Models\LessonEvaluation;
use App\Models\User;
use App\Transformers\ApiItemSerializer;
use App\Transformers\LessonEvaluationTransformer;

class LessonEvaluationController extends RestController
{	
	public function get(Request $request, $id)
	{
		try {
			$lessonEvaluation = GetLessonEvaluationService::get($request->user(), $id);
			$fractal = new Fractal\Manager();
			$fractal->setSerializer(new ApiItemSerializer);
			$item = new Fractal\Resource\Item($lessonEvaluation, new LessonEvaluationTransformer);
			$data = $fractal->createData($item)->toArray(); 
			return $this->success($data);
		} catch (ModelNotFoundException $e) {
			return $this->notFound();
		} catch (AuthorizationException $e) {
			return $this->unauthorized();
		} catch (\Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function getAll(Request $request, $id) {
		try {
			$user = User::findOrFail($id);
			$paginator = GetLessonEvaluationService::getEvaluationNotes($user);
			$paginatorAdapter = new IlluminatePaginatorAdapter($paginator);
			$fractal = new Fractal\Manager();
			$items = new Fractal\Resource\Collection($paginator->getCollection(), new LessonEvaluationTransformer());
			$items->setPaginator($paginatorAdapter);
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

	public function evaluate(Request $request, $id)
	{
		try {
			DB::beginTransaction();
			$lessonEvaluation = LessonEvaluation::findOrFail($id);
			if ($request->user()->id != $lessonEvaluation->user_id) {
				throw new AuthorizationException();
			}
			$lessonEvaluationService = new UpdateLessonEvaluationService();
			$lessonEvaluationService->update($lessonEvaluation, $request->all());
			DB::commit();
			return $this->success(['msg' => __('evaluation.update_success')]);
		} catch (ValidationException $e) {
			DB::rollback();
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->badRequest($lessonEvaluationService->getValidator()->errors()->all());
		} catch (ModelNotFoundException $e) {
			DB::rollback();
			return $this->notFound();
		} catch (AuthorizationException $e) {
			DB::rollback();
			return $this->unauthorized();
		} catch (\Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}	
	}

}