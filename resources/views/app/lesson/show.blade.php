@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>@lang('lesson.resume')</h1>
			</div>
			<div class="panel-body">
				<lesson :id="{{$lessonId}}">
				</lesson>
			</div>
		</div>
	</div>
</div>
@endsection