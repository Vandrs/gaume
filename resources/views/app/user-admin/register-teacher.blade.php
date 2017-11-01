@extends('layouts.app')
@section('content')
	@if(isset($id))
	<teacher-admin-registration id="{{$id}}">
	@else
	<teacher-admin-registration>
	@endif
	</teacher-admin-registration>
@endsection