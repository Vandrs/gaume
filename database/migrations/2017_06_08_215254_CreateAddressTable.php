<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('address', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('user_id')->nullable(false);
            $table->integer('state_id')->nullable(false);
            $table->integer('city_id')->nullable(false);
            $table->integer('neighborhood_id')->nullable(false);
            $table->string('street',100)->nullable(false);
            $table->string('number',20)->nullable(false);
            $table->string('complement',100)->nullable(true);
            $table->foreign('user_id')->references('id')->on('users');
            $table->foreign('state_id')->references('id')->on('states');
            $table->foreign('city_id')->references('id')->on('cities');
            $table->foreign('neighborhood_id')->references('id')->on('neighborhoods');
            $table->index('user_id');
            $table->index('state_id');
            $table->index('city_id');
            $table->index('neighborhood_id');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('address');
    }
}
