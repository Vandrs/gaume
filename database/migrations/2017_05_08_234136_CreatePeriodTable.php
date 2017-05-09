<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('periods',function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('lesson_id')->nullable(false);
            $table->smallInteger('hours')->nullable(false);
            $table->decimal('hour_value',10,2);
            $table->smallInteger('status');
            $table->boolean('billed');
            $table->dateTime('finished_at');
            $table->timestamps();
            $table->foreign('lesson_id')->references('id')->on('lessons');
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
        Schema::drop('periods');
    }
}
