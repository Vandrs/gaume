@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12">
		<teacher-list gameid="{{$gameId}}">
		</teacher-list>
	</div>
</div>
@endsection