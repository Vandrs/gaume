<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Enums\EnumLessonStatus;
use App\Models\Period;
use App\Services\Lesson\EndPeriodService;
use App\Services\Lesson\EndLessonService;
use App\Services\Lesson\CreateLessonEvaluationService;
use DB;
use Log;

class CheckInProgressPeriod implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $period;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Period $period)
    {
        $this->period = $period;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        DB::beginTransaction();
        try {
            $this->period->fresh();
            $periodService = new EndPeriodService();
            if ($periodService->canFinishPeriod($this->period)) {
                $periodService->finishPeriod($this->period);
                $lessonService = new EndLessonService();
                if ($lessonService->mustEndLesson($this->period->lesson)) {
                    $lessonService->endLesson($this->period->lesson);
                    $this->createEvaluation();
                }
            }
            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
            Log::error($e->getMessage());
            Log::error($e->getTraceAsString());
            throw $e;
        }   
    }

    private function createEvaluation()
    {
        $this->period->lesson->fresh();
        if ($this->period->lesson->status == EnumLessonStatus::FINISHED) {
            $lessonEvaluationService = new CreateLessonEvaluationService();
            $lessonEvaluationService->create($this->period->lesson);
        }
    }
}
