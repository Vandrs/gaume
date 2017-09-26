<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use App\Models\Lesson;
use Lang;
use Carbon\Carbon;

class EvaluateClassNotification extends Notification
{
    use Queueable;

    private $lesson;
    private $user;

    public function __construct(Lesson $lesson, User $user)
    {
        $this->lesson = $lesson;
        $this->user   = $user;
    }

    public function via($notifiable)
    {
        return ['database','broadcast'];
    }

    public function toArray($notifiable)
    {

        $data = [
            'game'    => $this->lesson->game->name, 
            'date'    => $this->lesson->created_at->format('d/m/Y'),
            'profile' => Lang::get('app.roles.'.$this->user->role_id), 
            'user'    => $this->user->name,
        ];

        return [
            'title'      => Lang::get('evaluation.notification_subject'),
            'body'       => Lang::get('evaluation.notification_body', $data),
            'action_url' => route('lessons.show',['id' => $this->lesson->id]),
            'created'    => Carbon::now()->format('Y-m-d H:i:s'),
            'icon'       => url('/images/favicon/favicon-96x96.png')
        ];
    }

    public function handle()
    {
        $this->user->fresh();
        $this->user->notify($this);
    }

}
