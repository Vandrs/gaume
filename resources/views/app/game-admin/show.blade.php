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
					<div class="col-xs-12 col-md-8 col-md-offset-2 text-left">
						<h4>{{isset($id) && $id  ? Lang::get('games.edit_game')  : Lang::get('games.add_new_game') }}</h4>
					</div>
				</div>
				@if(isset($id) && $id)
				<game-admin :id="{{$id}}">
				</game-admin>
				@else
				<game-admin>
				</game-admin>
				@endif
			</div>
		</div>
	</div>
</div>
@endsection