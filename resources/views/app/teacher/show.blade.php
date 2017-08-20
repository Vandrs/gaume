@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12">
		<teacher-profile id="{{$id}}">
		</teacher-profile>
	</div>
</div>
@if(config('app.disqus'))
	@include('partials.disqus',['page_url' => $page_url, 'page_id' => $page_id])
@endif
@endsection