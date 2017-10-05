@extends('layouts.app')
@section('content')
<div class="row">
	<div class="col-xs-12">
		<contact>
		</contact>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 text-center">
		<h2>@lang('faq.title')</h2>
	</div>
</div>
<div class="row">
	<div class="col-xs-12 col-md-offset-2 col-md-8">
		@if($user->hasRole(EnumRole::TEACHER) || $user->hasRole(EnumRole::ADMIN))
		@if($user->hasRole(EnumRole::ADMIN))
		<div class='row'>
			<div class="col-xs-12">
				<h2>@lang('faq.title_teacher')</h2>
			</div>
		</div>	
		@endif
		<div class='row'>
			<div class="col-xs-12">
				<h4>@lang('faq.teacher.question_1.title')</h4>
				<p>@lang('faq.teacher.question_1.question')</p>		
			</div>
		</div>
		<div class='row'>
			<div class="col-xs-12">
				<h4>@lang('faq.teacher.question_2.title')</h4>
				<p>@lang('faq.teacher.question_2.question')</p>		
			</div>
		</div>
		<div class='row'>
			<div class="col-xs-12">
				<h4>@lang('faq.teacher.question_3.title')</h4>
				<p>@lang('faq.teacher.question_3.question')</p>		
			</div>
		</div>
		<div class='row'>
			<div class="col-xs-12">
				<h4>@lang('faq.teacher.question_4.title')</h4>
				<p>@lang('faq.teacher.question_4.question')</p>		
			</div>
		</div>
		<div class='row'>
			<div class="col-xs-12">
				<h4>@lang('faq.teacher.question_5.title')</h4>
				<p>@lang('faq.teacher.question_5.question')</p>		
			</div>
		</div>
		<div class='row'>
			<div class="col-xs-12">
				<h4>@lang('faq.teacher.question_6.title')</h4>
				<p>@lang('faq.teacher.question_6.question')</p>		
			</div>
		</div>
		<div class='row'>
			<div class="col-xs-12">
				<h4>@lang('faq.teacher.question_7.title')</h4>
				<p>@lang('faq.teacher.question_7.question')</p>		
			</div>
		</div>
		@endif
		@if($user->hasRole(EnumRole::STUDENT) || $user->hasRole(EnumRole::ADMIN))
		@if($user->hasRole(EnumRole::ADMIN))
		<div class='row'>
			<div class="col-xs-12">
				<h2>@lang('faq.title_student')</h2>
			</div>
		</div>	
		@endif
		<div class='row'>
			<div class="col-xs-12">
				<h4>@lang('faq.student.question_1.title')</h4>
				<p>@lang('faq.student.question_1.question')</p>		
			</div>
		</div>
		<div class='row'>
			<div class="col-xs-12">
				<h4>@lang('faq.student.question_2.title')</h4>
				<p>@lang('faq.student.question_2.question')</p>		
			</div>
		</div>
		<div class='row'>
			<div class="col-xs-12">
				<h4>@lang('faq.student.question_3.title')</h4>
				<p>@lang('faq.student.question_3.question')</p>		
			</div>
		</div>
		<div class='row'>
			<div class="col-xs-12">
				<h4>@lang('faq.student.question_4.title')</h4>
				<p>@lang('faq.student.question_4.question')</p>		
			</div>
		</div>
		<div class='row'>
			<div class="col-xs-12">
				<h4>@lang('faq.student.question_5.title')</h4>
				<p>@lang('faq.student.question_5.question')</p>		
			</div>
		</div>
		<div class='row'>
			<div class="col-xs-12">
				<h4>@lang('faq.student.question_6.title')</h4>
				<p>@lang('faq.student.question_6.question')</p>		
			</div>
		</div>
		<div class='row'>
			<div class="col-xs-12">
				<h4>@lang('faq.student.question_7.title')</h4>
				<p>@lang('faq.student.question_7.question')</p>		
			</div>
		</div>
		@endif
		<div class="row">
			<div class="col-xs-12">
				<span class="help-block">@lang('faq.faq_obs')</span>
			</div>
		</div>
	</div>
</div>
@endsection