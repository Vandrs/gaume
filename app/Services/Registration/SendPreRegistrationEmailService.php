<?php 

namespace App\Services\Registration;

use App\Models\PreRegistration;
use App\Enums\EnumQueue;
use App\Mail\PreRegistrationEmail;
use Config;
use Mail;

class SendPreRegistrationEmailService
{
	public static function send(PreRegistration $preRegistration) 
	{
		$connection = Config::get('queue.default');
		$job = new PreRegistrationEmail($preRegistration);
		$job->onConnection($connection)
			->onQueue(EnumQueue::EMAIL);
		Mail::queue($job);
	}
}