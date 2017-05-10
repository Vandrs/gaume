<?php 

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Gate;
use Log;
use App\Enums\EnumPolicy;
use App\Services\Lesson\LessonService;
use App\Exceptions\ValidationException;
use App\Exceptions\AuthorizationException;
use Exception;

class LessonController extends RestController 
{
	public function get(Request $request, $id)
	{
		return $this->success(['id' => $id]);
	}		

	public function list()
	{

	}

	public function create(Request $request)
	{
		try {
			if (Gate::denies(EnumPolicy::CREATE_LESSON, $request->user())) {
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

	public function update()
	{

	}
}

