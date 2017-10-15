<?php 

namespace App\Services\Message;

use Validator;
use Carbon\Carbon;
use App\Models\User;
use App\Exceptions\ValidationException;
use Cmgmyr\Messenger\Models\Thread;
use Cmgmyr\Messenger\Models\Message;
use Cmgmyr\Messenger\Models\Participant;
use App\Jobs\MessageNotification;

class CreateThreadMessageService
{

	private $validator;

	public function create(User $user, $data)
	{
		$this->validator = Validator::make($data, $this->validation(), $this->messages());
		if ($this->validator->fails())  {
			throw new ValidationException('Parâmetros inválidos');
		}

		$subject = (isset($data['subject']) && !empty($data['subject'])) ? trim($data['subject']) : __('messages.new_contact',['name' => $user->name]);

		$thread = Thread::create([
            'subject' => $subject,
        ]);
        
        $message = Message::create([
            'thread_id' => $thread->id,
            'user_id' 	=> $user->id,
            'body' 		=> trim($data['message']),
        ]);
        
        Participant::create([
            'thread_id' => $thread->id,
            'user_id' 	=> $user->id,
            'last_read' => Carbon::now(),
        ]);
        
        $thread->addParticipant($data['recipients']);

        $participans = is_array($data['recipients'])? $data['recipients'] : explode(',', $data['recipients']);
        $this->dispatchNotifications($message, $participans);
        
        return $thread;
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
		return [
			'recipients.required' 	=> __('messages.recipients_required'),
			'message.required' 		=> __('messages.message_required')
		];	
	}

	public function getValidator() {
		return $this->validator;
	}

	private function dispatchNotifications(Message $message, $participants) 
	{
		foreach ($participants as $id) {
			$user = User::find($id);
			if ($user) {
				$job = new MessageNotification($user, $message);
				$job->onQueue(EnumQueue::CHAT);
				dispatch($job);
			}	
		}
	}
}