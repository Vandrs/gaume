<?php 

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Gate;
use Log;
use App\Enums\EnumPolicy;
use App\Models\Lesson;
use App\Models\Period;
use App\Services\Lesson\CreatePeriodService;
use App\Exceptions\ValidationException;
use App\Exceptions\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class PeriodController extends RestController 
{
	public function create(Request $request, $lessonId)
	{
		try {
			$lesson = Lesson::findOrFail($lessonId);
			if (Gate::denies(EnumPolicy::CREATE_PERIOD, $lesson)) {
				throw new AuthorizationException('Acesso Negado!!!');
			}
			$periodService = new CreatePeriodService();
			$period = $periodService->createToRenewLesson($lesson);
			return $this->created($period->id);
		} catch (AuthorizationException $e) {
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->unauthorized($e->getMessage());
		} catch (ValidationException $e) {
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->badRequest($lessonService->getValidator()->errors()->all());
		} catch (ModelNotFoundException $e) {
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->notFound();
		} catch (Exception $e) {
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function confirm(Request $request, $lessonId, $id)
	{
		try {
			$lesson = Lesson::findOrFail($lessonId);
			$period = Period::query()
							->where('id',$id)
							->where('lesson_id',$lesson->id)
							->firstOrFail();
			if (Gate::denies(EnumPolicy::CONFIRM_PERIOD, $lesson, $period)) {
				throw new AuthorizationException('Acesso Negado!!!');
			}
			$periodService = new CreatePeriodService();
			$period = $periodService->confirm($period, $request->only(['confirmed']));
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

