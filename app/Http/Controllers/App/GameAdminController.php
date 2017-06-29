<?php

namespace App\Http\Controllers\App;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class GameAdminController extends Controller
{
    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('app.game-admin.index');
    }

    public function create() 
    {
    	return view('app.game-admin.show');
    }

    public function update($id)
    {
    	return view('app.game-admin.show', ['id' => $id]);	
    }
}
