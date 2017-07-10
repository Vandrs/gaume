<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PreRegistrationTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('pre_registration', function(Blueprint $table) {
            $table->increments('id');
            $table->string('name',255);
            $table->string('email',255)->unique();
            $table->string('code',255)->unique();
            $table->text('games');
            $table->dateTime('mailed_at')->nullable();
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
        Schema::dropIfExists('pre_registration');
    }
}
