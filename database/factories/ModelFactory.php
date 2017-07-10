<?php

/*
|--------------------------------------------------------------------------
| Model Factories
|--------------------------------------------------------------------------
|
| Here you may define all of your model factories. Model factories give
| you a convenient way to create models for testing and seeding your
| database. Just tell the factory how a default model should look.
|
*/

/** @var \Illuminate\Database\Eloquent\Factory $factory */
$factory->define(\App\Models\User::class, function (Faker\Generator $faker) {
    return [
        'name' => $faker->name,
        'role_id' => null,
        'email' => $faker->unique()->safeEmail,
        'password' => bcrypt('12345678'),
        'remember_token' => str_random(10),
        'status' => \App\Enums\EnumUserStatus::ACTIVE
    ];
});


$factory->define(\App\Models\Lesson::class, function () {
	return [
		'teacher_id' => null,
		'student_id' => null,
		'status' 	 => null
	];
});

$factory->define(\App\Models\Period::class, function() {
    return [
        'lesson_id' => null,
        'hours' => 1,
        'hour_value' => 20,
        'status' => \App\Enums\EnumLessonStatus::IN_PROGRESS
    ];
});

