<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonEvaluationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('lesson_evaluations',function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('lesson_id');
            $table->unsignedInteger('user_id');
            $table->string('type',10);
            $table->smallInteger('status');
            $table->integer('note')->nullable();
            $table->text('comment')->nullable();
            $table->string('code',255)->nullable();
            $table->timestamps();
            $table->index('note');
            $table->index('type');
            $table->foreign('lesson_id')->references('id')->on('lessons');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('lesson_evaluations');
    }
}

