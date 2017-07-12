<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PreRegistrationController extends Controller
{
    
    public function registerTeacher() 
    {
        return view('app.user-admin.register-teacher');
    }
}



	