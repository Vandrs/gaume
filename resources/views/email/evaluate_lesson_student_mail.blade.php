@extends('email.template')
@section('content')
	{!! __("email.evaluate_lesson_email.student_body", ['link' => $link, 'date' => $date, 'game' => $game, 'student' => $student, 'teacher' => $teacher]) !!}
@endsection