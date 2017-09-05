<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTransactionsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('transactions', function (Blueprint $table) {
            $table->increments('id');
            $table->string('transaction_id', 255);
            $table->unsignedInteger('monzy_point_id');
            $table->smallInteger('status');
            $table->smallInteger('type');
            $table->dateTime('last_event')->nullable(true);
            $table->boolean('must_update')->default(true);
            $table->timestamps();
            $table->softDeletes();
            $table->foreign('monzy_point_id')
                  ->references('id')
                  ->on('monzy_points');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('transactions');
    }
}
