@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>{{ Lang::get('app.menu.games') }}</h1>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12 text-left">
						<a class="btn btn-primary" href="{{route('game-admin.create')}}"><i class="glyphicon glyphicon-plus-sign"></i> {{Lang::get('games.buttons.add_new')}}</a>
					</div>
				</div>
				<game-admin-list>
				</game-admin-list>
			</div>
		</div>
	</div>
</div>
@endsection