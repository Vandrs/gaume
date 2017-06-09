@if(session()->has('flash_error'))
<div class='col-xs-12'>
	<div class="alert alert-danger">
		{{session()->get('flash_error')}}
	</div>
</div>
@endif