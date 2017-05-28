<?php 

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use Gate;
use Log;
use DB;
use App\Enums\EnumPolicy;
use App\Models\Lesson;
use App\Models\Period;
use App\Services\Lesson\CreatePeriodService;
use App\Services\Lesson\ConfirmPeriodService;
use App\Exceptions\ValidationException;
use App\Exceptions\AuthorizationException;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use Exception;

class PeriodController extends RestController 
{
	public function create(Request $request, $lessonId)
	{
		try {
			DB::beginTransaction();
			$lesson = Lesson::findOrFail($lessonId);
			if (Gate::denies(EnumPolicy::CREATE_PERIOD, $lesson)) {
				throw new AuthorizationException('Acesso Negado!!!');
			}
			$periodService = new CreatePeriodService();
			$period = $periodService->createToRenewLesson($lesson);
			DB::commit();
			return $this->created($period->id);
		} catch (AuthorizationException $e) {
			DB::rollback();
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->unauthorized($e->getMessage());
		} catch (ValidationException $e) {
			DB::rollback();
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->badRequest($periodService->getValidator()->errors()->all());
		} catch (ModelNotFoundException $e) {
			DB::rollback();
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->notFound();
		} catch (Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}

	public function confirm(Request $request, $lessonId, $id)
	{
		try {
			DB::beginTransaction();
			$period = Period::with('lesson')
							->where('id',$id)
							->where('lesson_id',$lessonId)
							->firstOrFail();
			if (Gate::denies(EnumPolicy::CONFIRM_PERIOD, $period)) {
				throw new AuthorizationException('Acesso Negado!!!');
			}
			$periodService = new ConfirmPeriodService();
			$period = $periodService->confirm($period, $request->only(['confirmed']));
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
			return $this->unauthorized();
		} catch (ValidationException $e) {
			DB::rollback();
			Log::info($e->getMessage());
			Log::info($e->getTraceAsString());
			return $this->badRequest($periodService->getValidator()->errors()->all());
		} catch (Exception $e) {
			DB::rollback();
			Log::error($e->getMessage());
			Log::error($e->getTraceAsString());
			return $this->internalError();
		}
	}
}

