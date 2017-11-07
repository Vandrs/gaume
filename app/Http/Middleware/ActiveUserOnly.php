<?php

namespace App\Http\Middleware;

use Closure;
use Lang;
use Auth;
use App\Enums\EnumActiveInactive;

class ActiveUserOnly
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($request->user()->status == EnumActiveInactive::ACTIVE) {
            return $next($request);
        } else {
            Auth::guard()->logout();
            if ($request->ajax()) {
                $response = response()->json(null, Response::HTTP_UNAUTHORIZED);
                $response->setData(['msg' =>  Lang::get('messages.inactive_user')]);
                return $response;
            } else {
                session()->flash('msg_error', Lang::get('messages.inactive_user'));
                session()->flash('class_error', 'alert-danger');
                return redirect('/login');    
            }
        }
    }
}
