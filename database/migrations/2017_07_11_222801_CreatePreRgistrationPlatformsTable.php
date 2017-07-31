<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePreRgistrationPlatformsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create("pre_registration_platforms", function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('pre_registration_id');
            $table->unsignedInteger('game_platform_id');
            $table->timestamps();

            $table->foreign('pre_registration_id')
                  ->references('id')
                  ->on('pre_registration');

            $table->foreign('game_platform_id')
                  ->references('id')
                  ->on('game_platform');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('pre_registration_platforms');
    }
}
