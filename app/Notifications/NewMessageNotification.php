<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use App\Models\User;
use Carbon\Carbon;

class NewMessageNotification extends Notification
{
    use Queueable;

    private $user;
    private $message;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct(User $user, $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    public function via($notifiable)
    {
        return ['database', 'broadcast'];
    }   

    public function toArray($notifiable)
    {
        return [
            'title'      => __('messages.new_contact',['name' => $this->message['user']['nickname']]),
            'body'       => $this->message['truncated_message'],
            'action_url' => route('messages'),
            'created'    => Carbon::now()->format('Y-m-d H:i:s'),
            'icon'       => $this->message['user']['photo']
        ];
    }

    public function getUser()
    {
        return $this->user;
    }

    public function handle()
    {
        $this->user->fresh();
        $this->user->notify($this);
    }
}
