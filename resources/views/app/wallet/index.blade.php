@extends('layouts.app')
@section('content')
@if(session()->has('errors'))
<div class="row">
	<div class="col-xs-12">
		<div class="alert {{session()->get('error_class','alert-danger')}}">
			<ul class="inline-list">
				@foreach(session()->get('errors') as $error)
				{{$error}}
				@endforeach
			</ul>
		</div>
	</div>
</div>
@endif
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
	<div class="col-xs-12">
		<div class="pull-left">
			<span class="img-coins">
			</span>
		</div>
		<div class="pull-left">
			<h4 class="credit-title">@lang('wallet.your_credit')</h4>
			<strong>{{number_format($wallet->amount, '2', ',' ,'.')}}</strong> @lang('wallet.coins')
		</div>
	</div>
</div>
<div class="row">
	<div class="col-xs-12">
		<h2>@lang('wallet.packages')</h2>
	</div>
</div>
<div class="row">
	@foreach($monzyPoints as $monzyPoint)
		<div class="col-xs-12 col-sm-3">
			<div class="row">
				<div class="col-xs-12">
					<div class="plan-package text-center">
						<h4>{{$monzyPoint->name}}</h4>
						<h2>{{$monzyPoint->points}}</h2>
						<div>@lang('wallet.coins')</div>
						@if($monzyPoint->bonus)
						<div class="bonus-points">+ {{$monzyPoint->bonus}} @lang('wallet.free')</div>
						@else
						<div>&nbsp;</div>
						@endif
						<div>
							<form method="post" action="{{route('pagseguro.payment',['id' => $monzyPoint->id])}}">
							{{csrf_field()}}
							<button type="submit" class="btn btn-default value">R$ {{number_format($monzyPoint->value, 2, ',', '.')}}</button>
							</form>
						</div>
						<div class="submit-area margin-top-10">
							<form method="post" action="{{route('pagseguro.payment',['id' => $monzyPoint->id])}}">
							{{csrf_field()}}
							<button class="btn btn-default buy" type="submit">Comprar</button>
							</form>
						</div>
					</div>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					
				</div>
			</div>
		</div>
	@endforeach
</div>
<transaction-list>
</transaction-list>
@endsection