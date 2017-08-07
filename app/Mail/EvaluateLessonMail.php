<?php

namespace App\Mail;

use Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Models\LessonEvaluation;
use App\Enums\EnumStatus;
use App\Enums\EnumRole;

class EvaluateLessonMail extends Mailable
{
    use Queueable, SerializesModels;

    private $lessonEvaluation;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(LessonEvaluation $lessonEvaluation)
    {
        $this->lessonEvaluation = $lessonEvaluation;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {

        $date = $this->lessonEvaluation->lesson->created_at->format('d/m/Y');
        $mailData = [
            'student' => $this->lessonEvaluation->lesson->student->name,
            'teacher' => $this->lessonEvaluation->lesson->teacher->name,
            'date'    => $date,
            'game'    => $this->lessonEvaluation->lesson->game->name,
            'link'    => route('lessons.show', ['id' => $this->lessonEvaluation->lesson->id])
        ];

        $view = $this->lessonEvaluation->type == EnumRole::STUDENT ? 'evaluate_lesson_teacher_mail' : 'evaluate_lesson_student_mail';

        return $this->to($this->lessonEvaluation->user->email, $this->lessonEvaluation->user->name)
                    ->subject(__('email.evaluate_lesson_email.subject',['name' => $this->lessonEvaluation->user->name, 'date' => $date]))
                    ->view('email.'.$view, $mailData);
    }

    public function handle() 
    {
        $this->lessonEvaluation->fresh();
        if ($this->lessonEvaluation->status != EnumStatus::PENDING) {
            return;
        }
        Mail::send($this);
    }
}
