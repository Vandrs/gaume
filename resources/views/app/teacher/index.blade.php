@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="panel panel-default">
			<div class="panel-heading">
				<h1>@lang('app.menu.teachers')</h1>
			</div>
			<div class="panel-body">
				<div class="row">
					<div class="col-xs-12">
						<table table class="table table-default table-striped">
							<thead>
								<tr>
									<th>Nome</th>
									<th>Email</th>
									<th>Ações</th>
								</tr>
							</thead>
							<tbody>
								@foreach($teachers as $teacher)
								<tr>
									<td>{{$teacher->name}}</td>
									<td>{{$teacher->email}}</td>
									<td>
										<button class="btn btn-default">
											Iniciar Aula	
										</button>
									</td>
								</tr>
								@endforeach
							</tbody>
						</table>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection