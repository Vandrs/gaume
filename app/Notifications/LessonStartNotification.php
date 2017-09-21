<?php 

namespace App\Notifications;

use App\Models\User;
use App\Models\Lesson;
use NotificationChannels\WebPush\WebPushMessage;
use NotificationChannels\WebPush\WebPushChannel;
use Illuminate\Bus\Queueable;
use Illuminate\Notifications\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Config;
use Carbon\Carbon;

class LessonStartNotification extends Notification implements ShouldQueue
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
		return ['database', 'broadcast', WebPushChannel::class];
	}	

	public function toArray($notifiable)
    {
        return [
            'title' 	 => __('lesson.new_class_invitation'),
            'body' 		 => __('lesson.class_invitation_body',['name' => $this->lesson->teacher->name]),
            'action_url' => route('lessons.show',['id' => $this->lesson->id]),
            'created' 	 => Carbon::now()->format('Y-m-d H:i:s'),
            'icon' 		 => $this->getNotificationIcon()
        ];
    }

	public function toWebPush($notifiable, $notification)
    {
		return WebPushMessage::create()
							 ->id($notification->id)
							 ->icon($this->getPushNotificationIcon())
            				 ->title(Config::get('app.name'))
            				 ->body(__('lesson.class_invitation_body',['name' => $this->lesson->teacher->name]))
            				 ->action(__('lesson.see_class_now'), route('lessons.show',['id' => $this->lesson->id]));	
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

	private function getNotificationIcon()
	{
		return $this->lesson->student->getPhotoProfileUrl();
	}

	private function getPushNotificationIcon() 
	{
		return $this->lesson->student->getPhotoProfileUrl() ? $this->lesson->student->getPhotoProfileUrl() : url('/images/favicon/favicon-96x96.png');
	}
}