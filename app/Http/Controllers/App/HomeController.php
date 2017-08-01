<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Auth;
use App\Enums\EnumRole;

class HomeController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
    	$user = Auth::user();

    	if ($user->hasRole(EnumRole::TEACHER)) {
    		return redirect()->route('lessons.list');
    	} else if ($user->hasRole(EnumRole::ADMIN)) {
    		return redirect()->route('user-admin.list');
    	} 
        return view('home');
    }
}
