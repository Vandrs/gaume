<?php

namespace App\Http\Controllers\App;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FaqController extends Controller
{
	public function index(Request $request)
	{
		return view('app.faq.contact');
	}

	public function contactList(Request $request)
	{
		return view('app.faq.contact-list');	
	}
}