<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Lesson\EndLessonService;
use App\Services\Lesson\CreateLessonEvaluationService;
use App\Enums\EnumLessonStatus;
use App\Models\Lesson;
use DB;
use Log;

class CheckPendingLesson implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    private $lesson;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Lesson $lesson)
    {
        $this->lesson = $lesson;
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
            $this->lesson->fresh();
            $lessonService = new EndLessonService();
            if ($lessonService->mustEndLesson($this->lesson)) {
                $lessonService->endLesson($this->lesson);
                $this->createEvaluation();
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
        $this->lesson->fresh();
        if ($this->lesson->status == EnumLessonStatus::FINISHED) {
            $lessonEvaluationService = new CreateLessonEvaluationService();
            $lessonEvaluationService->create($this->lesson);
        }
    }
}
