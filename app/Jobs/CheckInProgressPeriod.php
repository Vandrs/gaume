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
