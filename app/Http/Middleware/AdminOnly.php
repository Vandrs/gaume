<?php

namespace App\Http\Middleware;

use Closure;
use Lang;
use App\Enums\EnumRole;
use Illuminate\Http\Response;

class AdminOnly
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
        if ($request->user()->hasRole(EnumRole::ADMIN)) {
            return $next($request);
        } else {
            if ($request->ajax()) {
                $response = response()->json(null, Response::HTTP_UNAUTHORIZED);
                $response->setData(['msg' =>  Lang::get('app.messages.unauthorized')]);
                return $response;
            } else {
                session()->flash('msg_error', Lang::get('app.messages.unauthorized'));
                return redirect()->route('home');    
            }
        }
    }
}
