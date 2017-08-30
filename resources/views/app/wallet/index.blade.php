@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12 text-center">
		<span class="fa-stack fa-lg">
				<i class="fa fa-circle fa-stack-2x yellow-icon"></i>
				<i class="fa fa-credit-card-alt fa-stack-1x fa-inverse"></i>
		</span>
	</div>
	<div class="col-xs-12 text-center">
		<h1>@lang('app.menu.wallet')</h1>
	</div>
</div>
<div class="row">
	@foreach($monzyPoints as $monzyPoint)
		<div class="col-xs-12 col-sm-3 col-md-4">
			<div class="row">
				<div class="col-xs-12">
					<h2>{{$monzyPoint->points}} Monzy Points</h2>
					<p>+ bonus {{}} de monzy points <br/> R$ {{number_format($monzyPoint->value, 2, ',', '.')}}</p>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<form method="POST" action="{{route('pagseguro.payment',['id' => $monzyPoint->id])}}">
						<button class="btn btn-default" type="submit">Comprar</button>
					</form>
				</div>
			</div>
		</div>
	@endforeach
</div>
@endsection