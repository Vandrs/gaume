<?php

namespace App\Services\Notification;

class NotificationFormatterService
{
	public static function format($notification)
	{
		$message = trim(implode(PHP_EOL.PHP_EOL, $notification->intro));
        $message .= PHP_EOL.PHP_EOL.trim(implode(PHP_EOL.PHP_EOL, $notification->outro));
        return trim($message);
	}

	public static function payload($notification)
	{
		$payload = [
            'title' => isset($notifications->intro[0]) ? $notifications->intro[0] : null,
            'body' => self::format($notification),
            'actionText' => $notification->action_text ?: null,
            'actionUrl' => $notification->action_url ?: null,
            'id' => isset($notification->id) ? $notification->id : null,
        ];
        return $payload;
	}
}