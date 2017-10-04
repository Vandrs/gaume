<?php 

namespace App\Services\Contact;

use App\Models\Contact;
use App\Enums\EnumActiveInactive;

class MarkContactAsReadService
{
	public static function setRead(Contact $contact)
	{
		return $contact->update(['status' => EnumActiveInactive::ACTIVE]);
	}
}