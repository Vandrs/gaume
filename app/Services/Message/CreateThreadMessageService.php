<?php 

namespace App\Services\Message;
use App\Models\User;


class CreateThreadMessageService
{
	public function create(User $user, $data)
	{

	}

	public function validation() 
	{
		return [
			'message' 	 => 'required',
			'recipients' => 'required'
		];
	}

	public function messages()
	{
		
	}
}