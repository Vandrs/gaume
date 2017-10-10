<?php 

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;


class MessageController extends Controller
{
	public function index(Request $request)
	{
		return view('app.message-box.index',['userId' => $request->user()->id]);
	}
}