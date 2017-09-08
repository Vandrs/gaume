<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCheckoutReferenceTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transaction_references', function (Blueprint $table) {
            $table->increments('id');
            $table->string('code', 255)->unique();
            $table->unsignedInteger('user_id');
            $table->unsignedInteger('monzy_point_id');
            $table->timestamps();
            $table->foreign('monzy_point_id')
                  ->references('id')
                  ->on('monzy_points');
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
        Schema::drop('transaction_references');
    }
}
