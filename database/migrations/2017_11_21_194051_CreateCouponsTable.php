<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateCouponsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('coupons', function (Blueprint $table) { 
            $table->increments('id');
            $table->string('code',255)->nullable(false);
            $table->integer('coins')->nullable(false);
            $table->integer('use_limit')->nullable(false);
            $table->integer('used_times')->default(0)->nullable(false);
            $table->dateTime('valid_until')->nullable(true);;
            $table->timestamps();
            $table->index('code');
            $table->index('valid_until');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('coupons');
    }
}
