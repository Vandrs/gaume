<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lessons',function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('teacher_id')->nullable(false);
            $table->unsignedInteger('student_id')->nullable(false);
            $table->smallInteger('status');
            $table->dateTime('finished_at');
            $table->timestamps();
            $table->foreign('teacher_id')->references('id')->on('users');
            $table->foreign('student_id')->references('id')->on('users');
            $table->index('status');
            $table->index('finished_at');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lessons');
    }
}
