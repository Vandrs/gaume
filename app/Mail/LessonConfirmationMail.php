<?php

namespace App\Mail;

use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Enums\EnumLessonStatus;
use App\Enums\EnumRole;
use App\Models\User;
use App\Models\Lesson;
use Mail;
use Lang;

class LessonConfirmationMail extends Mailable
{
    use Queueable, SerializesModels;

    private $user;
    private $lesson;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(User $user, Lesson $lesson)
    {
        $this->user   = $user;
        $this->lesson = $lesson;
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $this->user->fresh();
        $this->lesson->fresh();

        $periods = $this->lesson->periods->filter(function($period){
            return $period->status == EnumLessonStatus::FINISHED;
        });

        $hours = $periods->sum('hours');
        $game = $this->lesson->game->name;
        $date = $this->lesson->created_at->format('d/m/Y');

        $mailData = [
            "teacher" => $this->lesson->teacher->name,
            "game"    => $game,
            "student" => $this->lesson->student->name,
            "date"    => $date,
            "hours"   => $hours,
            "link"    => route('lessons.show',['id' => $this->lesson->id])
        ];

        $view = $this->user->hasRole(EnumRole::TEACHER) ? 'email.confirmation_email_teacher' : 'email.confirmation_email_student';
                
        return $this->to($this->user->email, $this->user->name)
                    ->subject(Lang::get('email.lesson_confirmation_mail.subject',['game' => $game, 'date' => $date]))
                    ->view($view, $mailData);
    }

    public function handle()
    {
        Mail::send($this);
    }
}
