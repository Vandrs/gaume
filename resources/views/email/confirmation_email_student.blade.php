@extends('email.template')
@section('content')
	<br />
	{!! __("email.lesson_confirmation_mail.student_body", ['teacher' => $teacher, 'game' => $game, 'student' => $student, 'date' => $date, 'hours' => $hours, 'link' => $link]) !!}
@endsection