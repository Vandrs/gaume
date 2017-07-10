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
						<h4>@lang('users.add_new_teacher')</h4>
					</div>
				</div>
				<teacher-admin-registration>
				</teacher-admin-registration>
			</div>
		</div>
	</div>
</div>
@endsection