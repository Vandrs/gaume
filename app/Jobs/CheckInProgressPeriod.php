<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\Period;
use App\Services\Lesson\EndPeriodService;
use App\Services\Lesson\EndLessonService;

class CheckInProgressLesson implements ShouldQueue
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
        $this->period->reload();
        $periodService = new EndPeriodService();
        if ($periodService->canFinishPeriod()) {
            $periodService->finishPeriod($this->period);
            $lessonService = new EndLessonService();
            if ($lessonService->mustEndLesson($this->period->lesson)) {
                $lessonService->endLesson($this->period->lesson);
            }
        }
    }
}
