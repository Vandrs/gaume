<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use App\Models\User;
use Cmgmyr\Messenger\Models\Message;
use App\Transformers\MessageTransformer;
use App\Transformers\ApiItemSerializer;
use League\Fractal;
use App\Events\NewMessage;

class MessageNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */

    private $user;
    private $message;

    public function __construct(User $user, Message $message)
    {
        $this->user = $user;
        $this->message = $message;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $this->user->fresh();
        $this->message->fresh();
        $messageData = $this->getMessageData();
        broadcast(new NewMessage($this->user->id, $messageData));
    }

    private function getMessageData()
    {
        $fractal = new Fractal\Manager();
        $fractal->setSerializer(new ApiItemSerializer);
        $item = new Fractal\Resource\Item($this->message, new MessageTransformer());
        return $fractal->createData($item)->toArray(); 
    }
}
