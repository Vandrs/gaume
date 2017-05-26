<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Services\Lesson\EndLessonService;

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

    /**ck
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $lessonService = new EndLessonService();
        if ($lessonService->mustEndLesson($this->lesson)) {
            $lessonService->endLesson($this->lesson);
        }
    }
}
