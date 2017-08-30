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
	@foreach($monzyPoints as $monzyPoint)
		<div class="col-xs-12 col-sm-3 col-md-4">
			<div class="row">
				<div class="col-xs-12">
					{!!$monzyPoint->description(true)!!}
					<p>R$ {{number_format($monzyPoint->value, 2, ',', '.')}}</p>
				</div>
			</div>
			<div class="row">
				<div class="col-xs-12">
					<form method="post" action="{{route('pagseguro.payment',['id' => $monzyPoint->id])}}">
						{{csrf_field()}}
						<button class="btn btn-default" type="submit">Comprar</button>
					</form>
				</div>
			</div>
		</div>
	@endforeach
</div>
@endsection