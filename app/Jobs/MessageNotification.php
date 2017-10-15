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
use App\Notifications\NewMessageNotification;
use App\Enums\EnumQueue;
use \Pusher;
use Log;

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
    private $pusher = 'pusher';
    private $notification = 'notification';

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
        $mode = $this->getNotificationMode();

        if ($mode == $this->pusher) {
            broadcast(new NewMessage($this->user->id, $messageData));
        } else if ($mode == $this->notification) {
            $notification = new NewMessageNotification($this->user, $messageData);
            $notification->onQueue(EnumQueue::NOTIFICATION);
            dispatch($notification);
        }

    }

    private function getMessageData()
    {
        $fractal = new Fractal\Manager();
        $fractal->setSerializer(new ApiItemSerializer);
        $item = new Fractal\Resource\Item($this->message, new MessageTransformer());
        return $fractal->createData($item)->toArray(); 
    }

    private function getNotificationMode()
    {
        $config = config('broadcasting.connections.pusher');
        $pusher = new Pusher($config['key'], $config['secret'], $config['app_id'], $config['options']);

        if (config('app.debug')) {
            $logger = new CustomPusherLog();
            $pusher->set_logger($logger);
        }

        $info = $pusher->get_channel_info('private-chat-room.33',array('info' => 'subscription_count'));
        if ($info) {
            if ($info->subscription_count) {
                return $this->pusher;
            } else {
                return $this->notification;
            }
        }
        return $this->notification;
    }
}

class CustomPusherLog 
{
    public function log($msg)
    {
        Log::info($msg);
    }
}
