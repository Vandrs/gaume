<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PreRegistrationController extends Controller
{

	public function index()
	{
		return view('app.user-admin.list-pre-registration');
	}
    
    public function create() 
    {
        return view('app.user-admin.register-teacher');
    }

    public function show(Request $request, $id)
    {
    	return view('app.user-admin.register-teacher', ['id' => $id]);
    }
}



	