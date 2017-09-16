<?php 

namespace App\Http\Controllers\Api;

use Gate;
use Log;
use DB;
use Exception;
use App\Enums\EnumPolicy;
use App\Models\Lesson;
use App\Services\Lesson\CreateLessonService;
use App\Services\Lesson\ConfirmLessonService;
use App\Services\Lesson\GetLessonService;
use App\Transformers\LessonTransformer;
use App\Transformers\ApiItemSerializer;
use App\Transformers\LessonListTransformer;
use App\Exceptions\ValidationException;
use App\Exceptions\AuthorizationException;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use League\Fractal;
use League\Fractal\Pagination\IlluminatePaginatorAdapter;

class LessonController extends RestController 
{
	public function create(Request $request)
	{
		try {
			DB::beginTransaction();
			if (Gate::denies(EnumPolicy::CREATE_LESSON)) {
				throw new AuthorizationException('Acesso Negado!!!');
			}
			$lessonService = new CreateLessonService();
			$lesson = $lessonService->create($request->user(), $request->all());
			DB::commit();
			return $this->created($lesson->id);
		} catch (AuthorizationException $e) {
			DB::rollback();
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->unauthorized($e->getMessage());
		} catch (ValidationException $e) {
			DB::rollback();
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->badRequest($lessonService->getValidator()->errors()->all());
		} catch (Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function confirm(Request $request, $id)
	{
		try {
			DB::beginTransaction();
			$lesson = Lesson::findOrFail($id);
			if (Gate::denies(EnumPolicy::CONFIRM_LESSON, $lesson)) {
				throw new AuthorizationException('Acesso Negado!!!');
			}
			$lessonService = new ConfirmLessonService();
			$lesson = $lessonService->confirm($lesson, $request->only(['confirmed']));
			DB::commit();
			return $this->success();
		} catch (ModelNotFoundException $e) {
			DB::rollback();
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->notFound();
		} catch (AuthorizationException $e) {
			DB::rollback();
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->unauthorized($e->getMessage());
		} catch (ValidationException $e) {
			DB::rollback();
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->badRequest($lessonService->getValidator()->errors()->all());
		} catch (Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function get(Request $request, $id)
	{
		try {
			$lessonService = new GetLessonService();
			$lesson = $lessonService->get($id, $request->includes);
			if (Gate::denies(EnumPolicy::GET_LESSON, $lesson)) {
				throw new AuthorizationException('Acesso Negado!!!');
			}
			$fractal = new Fractal\Manager();
			if ($request->includes) {
				$fractal->parseIncludes($request->includes);
			}
			$fractal->setSerializer(new ApiItemSerializer);
			$item = new Fractal\Resource\Item($lesson, new LessonTransformer);
			$data = $fractal->createData($item)->toArray(); 
			return $this->success($data);
		} catch (ModelNotFoundException $e) {
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->notFound();
		} catch (AuthorizationException $e) {
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->unauthorized();
		} catch (ValidationException $e) {
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->badRequest($lessonService->getValidator()->errors()->all());
		} catch (Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function getAll(Request $request)
	{
		try {
			$lessonService = new GetLessonService();
			$user = $request->user();
			$lessonsPaginator = $lessonService->getAll($user, $request->all());
			$paginatorAdapter = new IlluminatePaginatorAdapter($lessonsPaginator);
			$fractal = new Fractal\Manager();
			$items = new Fractal\Resource\Collection($lessonsPaginator->getCollection(), new LessonListTransformer($user));
			$items->setPaginator($paginatorAdapter);
			$data = $fractal->createData($items)->toArray(); 
			return $this->success($data);	
		} catch (ModelNotFoundException $e) {
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->notFound();
		} catch (AuthorizationException $e) {
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->unauthorized();
		} catch (ValidationException $e) {
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->badRequest($lessonService->getValidator()->errors()->all());
		} catch (Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}
}

