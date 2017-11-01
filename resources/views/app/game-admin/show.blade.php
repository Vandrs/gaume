@extends('layouts.app')
@section('content')
	@if(isset($id) && $id)
	<game-admin :id="{{$id}}">
	</game-admin>
	@else
	<game-admin>
	</game-admin>
	@endif
@endsection