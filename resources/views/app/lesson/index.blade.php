@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>{{ Auth::user()->hasRole(EnumRole::ADMIN) ? Lang::get('lesson.lessons') : Lang::get('lesson.my_lessons') }}</h1>
			</div>
			<div class="panel-body">
				<lesson-list>
				</lesson-list>
			</div>
		</div>
	</div>
</div>
@endsection