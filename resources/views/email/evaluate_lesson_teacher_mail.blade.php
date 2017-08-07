@extends('email.template')
@section('content')
	{!! __("email.evaluate_lesson_email.teacher_body", ['link' => $link, 'date' => $date, 'game' => $game, 'student' => $student, 'teacher' => $teacher]) !!}
@endsection