<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUserEvaluationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('user_evaluation',function(Blueprint $table){
            $table->increments('id');
            $table->unsignedInteger('user_id');
            $table->float('note')->default(0);
            $table->integer('qtd_evaluations')->default(0);
            $table->timestamps();
            $table->foreign('user_id')
                  ->references('id')
                  ->on('users');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('user_evaluation');
    }
}
