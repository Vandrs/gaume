<?php 

namespace App\Http\Controllers\Api;

use Log;
use DB;
use Illuminate\Http\Request;
use Illuminate\Database\Eloquent\ModelNotFoundException;
use NotificationChannels\WebPush\PushSubscription;
use App\Http\Controllers\Api\RestController;
use App\Services\Notification\GetNotificationService;
use App\Services\Notification\NotificationReadService;
use App\Services\Notification\NotificationFormatterService;



class NotificationController extends RestController
{
	 /**
     * Get user's last notifications.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
    	try {
    		$data = GetNotificationService::getLastNotifications($request->user(), $request->limit);
			return $this->success($data);
    	} catch (\Exception $e) {
    		Log::error($e->getMessage());
    		Log::error($e->getTraceAsString());
    		return $this->internalError();
    	}
    }
    
    /**
     * Mark user's notification as read.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response= 
     */
    public function markAsRead(Request $request, $id)
    {
    	DB::beginTransaction();
    	try {
    		$user = $request->user();
        	$notification = GetNotificationService::getLastById($user, $id);
        	NotificationReadService::markAsRead($user, $notification);
        	DB::commit();
        	return $this->success();
    	}  catch (ModelNotFoundException $e) {
    		DB::rollback();
    		Log::error($e->getMessage());
    		Log::error($e->getTraceAsString());
    		return $this->notFound();
    	} catch (\Exception $e) {
    		DB::rollback();
    		Log::error($e->getMessage());
    		Log::error($e->getTraceAsString());
    		return $this->internalError();
    	}
    }

    /**
     * Mark all user's notifications as read.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function markAllRead(Request $request)
    {
    	DB::beginTransaction();
    	try {
    		NotificationReadService::markAllAsRead($request->user());
    		DB::commit();
        	return $this->success();
    	} catch (\Exception $e) {
    		DB::rollback();
    		Log::error($e->getMessage());
    		Log::error($e->getTraceAsString());
    		return $this->internalError();
    	}        
    }
    /**
     * Get user's last notification from database.
     *
     * This method will be accessed by the service worker
     * so the user is not authenticated and it requires an endpoint.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function last(Request $request)
    {
        if (empty($request->endpoint)) {
            return $this->forbidden('Endpoint missing.');
        }
        DB::beginTransaction();
        try {
        	$subscription = PushSubscription::findByEndpoint($request->endpoint);
        	if (is_null($subscription)) {
        		DB::rollback();
            	return $this->notFound();
        	}
        	$notification = GetNotificationService::getLastNotification($subscription->user);
        	$payload = NotificationFormatterService::payload($notification);
        	return $this->success($payload);
        } catch(ModelNotFoundException $e) {
        	DB::rollback();
        	Log::error($e->getMessage());
    		Log::error($e->getTraceAsString());
        	return $this->noutFound();
        } catch (\Exception $e) {
        	DB::rollback();
        	Log::error($e->getMessage());
    		Log::error($e->getTraceAsString());
    		return $this->internalError();
        }
    }
    /**
     * Mark the notification as read and dismiss it from other devices.
     *
     * This method will be accessed by the service worker
     * so the user is not authenticated and it requires an endpoint.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function dismiss(Request $request, $id)
    {
    	if (empty($request->endpoint)) {
            return $this->forbidden('Endpoint missing.');
        }
        DB::beginTransaction();
        try {
        	$subscription = PushSubscription::findByEndpoint($request->endpoint);
        	if (is_null($subscription)) {
        		DB::rollback();
            	return $this->notFound();
        	}
        	if (is_null($subscription)) {
        		DB::rollback();
            	return $this->notFound();
        	}
        	$notification = GetNotificationService::getLastById($user, $id);
        	NotificationReadService::markAsRead($subscription->user, $notification);
        	DB::commit();
        	return $this->success();
        } catch(ModelNotFoundException $e) {
        	DB::rollback();
        	Log::error($e->getMessage());
    		Log::error($e->getTraceAsString());
        	return $this->noutFound();
        } catch (\Exception $e) {
        	DB::rollback();
        	Log::error($e->getMessage());
    		Log::error($e->getTraceAsString());
    		return $this->internalError();
        }
    }
}