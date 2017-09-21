<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\RestController;

class PushSubscriptionController extends RestController
{
    /**
     * Update user's subscription.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        $this->validate($request, ['endpoint' => 'required']);
        $request->user()->updatePushSubscription(
            $request->endpoint,
            $request->key,
            $request->token
        );
        return $this->success();
    }
    /**
     * Delete the specified subscription.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $this->validate($request, ['endpoint' => 'required']);
        $request->user()->deletePushSubscription($request->endpoint);
        return $this->success();
    }
}