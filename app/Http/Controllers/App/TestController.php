<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class TestController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('email.pre_registration_mail', ['name' => 'Vanderson Nunes', 'link' => 'dsa dsadsa assdsda asd'] );
    }
}
