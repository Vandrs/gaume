<?php 

namespace App\Services\Contact;

use App\Models\Contact;

class DeleteContactService 
{
	public static function delete(Contact $contact)
	{
		return $contact->delete();
	}
}