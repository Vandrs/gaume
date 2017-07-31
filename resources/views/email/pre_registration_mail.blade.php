@extends('email.template')
@section('content')
	<br />
	{!! __("email.pre_registration_mail.body", ['name' => $name, 'link' => $link]) !!}
@endsection