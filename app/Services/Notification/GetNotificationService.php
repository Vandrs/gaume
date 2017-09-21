<?php 

namespace App\Services\Notification;

use App\Models\User;

class GetNotificationService
{
	public static function getLastNotifications(User $user, $limit = null)
	{
        $query = $user->unreadNotifications();
        if ($limit) {
            $limit =  (int) $limit;
            $query = $query->limit($limit);
        }

        $notifications = $query->get();
        $total = $user->unreadNotifications->count();

        $data = [
        	'notifications' => $notifications,
        	'total' 		=> $total
        ];

        return $data;
	}

    public static function getLastNotification(User $user)
    {
        return $user->unreadNotifications()->firstOrFail();
    }

	public static function getLastById(User $user, $id)
	{
        return $user->unreadNotifications()
                    ->where('id', '=', $id)
                    ->firstOrFail();
	}
}