<?php 

namespace App\Services\Notification;

use App\Models\User;
use App\Events\NotificationRead;
use App\Events\NotificationReadAll;

class NotificationReadService
{
	public static function markAsRead(User $user, $notification)
	{
		$notification->markAsRead();
        event(new NotificationRead($user->id, $notification->id));
	}

	public static function markAllAsRead(User $user)
	{
		$user->unreadNotifications()
             ->get()->each(function ($n) {
                 $n->markAsRead();
             });
        event(new NotificationReadAll($user->id));
	}
}