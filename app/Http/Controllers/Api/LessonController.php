<?php 

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Gate;
use Log;
use App\Enums\EnumPolicy;
use App\Models\Lesson;
use App\Services\Lesson\LessonService;
use App\Exceptions\ValidationException;
use App\Exceptions\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class LessonController extends RestController 
{
	public function create(Request $request)
	{
		try {
			if (Gate::denies(EnumPolicy::CREATE_LESSON)) {
				throw new AuthorizationException('Acesso Negado!!!');
			}
			$lessonService = new LessonService();
			$lesson = $lessonService->create($request->user(), $request->only(['teacher_id']));
			return $this->created($lesson->id);
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

	public function confirm(Request $request, $id)
	{
		try {
			$lesson = Lesson::findOrFail($id);
			if (Gate::denies(EnumPolicy::CONFIRM_LESSON, $lesson)) {
				throw new AuthorizationException('Acesso Negado!!!');
			}
			$lessonService = new LessonService();
			$lesson = $lessonService->confirm($lesson, $request->only(['confirmed']));
			return $this->success();
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

