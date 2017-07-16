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
            $table->string('state',2)->nullable(false);
            $table->string('city',255)->nullable(false);
            $table->string('neighborhood',255)->nullable(true);
            $table->string('street',100)->nullable(false);
            $table->string('number',20)->nullable(false);
            $table->string('complement',100)->nullable(true);
            $table->string('zipcode',10);
            $table->index('user_id');
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
