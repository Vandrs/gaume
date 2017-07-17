<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTeacherGamePlatformTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('teacher_game_platforms', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('teacher_game_id');
            $table->unsignedInteger('platform_id');
            $table->string('nickname',100);
            $table->timestamps();
            $table->foreign('teacher_game_id')->references('id')->on('teacher_game');
            $table->foreign('platform_id')->references('id')->on('platforms');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('teacher_game_platforms');
    }
}
