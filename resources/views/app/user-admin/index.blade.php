@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>@lang('app.menu.users')</h1>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12 text-left">
						<a class="btn btn-primary" href="{{route('user-admin.create-teacher')}}"><i class="glyphicon glyphicon-plus-sign"></i> @lang('users.buttons.add_new_teacher')</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection