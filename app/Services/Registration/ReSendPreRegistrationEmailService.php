<?php 

namespace App\Services\Registration;

use App\Models\PreRegistration;
use App\Enums\EnumQueue;
use App\Mail\PreRegistrationEmail;
use App\Services\Registration\SendPreRegistrationEmailService;
use App\Services\Registration\CreatePreRegistrationService;
use Config;
use Mail;

class ReSendPreRegistrationEmailService
{
	public static function send(PreRegistration $preRegistration) 
	{
		$code = CreatePreRegistrationService::generateCode($preRegistration);
		$preRegistration->update(['code' => $code]);
		SendPreRegistrationEmailService::send($preRegistration);
	}
}