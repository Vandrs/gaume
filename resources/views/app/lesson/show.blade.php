@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12">
		<lesson :id="{{$lessonId}}">
		</lesson>
	</div>
</div>
@endsection