@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>@lang('app.menu.my-games')</h1>
			</div>
			<div class="panel-body">
				<teacher-game>
				</teacher-game>
			</div>
		</div>
	</div>
</div>
@endsection