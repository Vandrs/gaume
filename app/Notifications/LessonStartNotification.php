<?php 

namespace App\Notifications;

use App\Models\Lesson;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use Illuminate\Notifications\Notification;

class LessonStartNotification extends Notification
{

	private $lesson;

	public function __construct(Lesson $lesson)
	{
		$this->lesson = $lesson;
	}

	public function via($notifiable)
	{
		return [WebPushChannel::class];
	}	


	public function toWebPush($notifiable, $notification)
    {
		return WebPushMessage::create()
							 ->id($notification->id)
            				 ->title(__('lesson.new_class_invitation'))
            				 ->body(__('lesson.class_invitation_body',['name' => $this->lesson->teacher->name]))
            				 ->action(__('lesson.see_class_now'), route('lessons.show',['id' => $this->lesson->id]));	
	}
}