<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class UserAdminController extends Controller
{
    
    public function index()
    {
        return view('app.user-admin.index');
    }
}
