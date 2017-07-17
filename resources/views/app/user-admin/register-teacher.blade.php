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
					<div class="col-xs-12 col-md-8 col-md-offset-2 text-left">
						@if(isset($id))
						<h4>@lang('users.edit_new_teacher')</h4>
						@else
						<h4>@lang('users.add_new_teacher')</h4>
						@endif
					</div>
				</div>
				@if(isset($id))
				<teacher-admin-registration id="{{$id}}">
				@else
				<teacher-admin-registration>
				@endif
				</teacher-admin-registration>
			</div>
		</div>
	</div>
</div>
@endsection