@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12">
		<billing-users dt_ini="{{$dt_ini->format('d/m/Y')}}" dt_end="{{$dt_end->format('d/m/Y')}}">
		</billing-users>
	</div>
</div>
@endsection