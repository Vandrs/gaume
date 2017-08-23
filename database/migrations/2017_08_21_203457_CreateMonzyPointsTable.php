<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use DB;

class CreateMonzyPointsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('monzy_points', function($table){
            $table->increments('id');
            $table->integer('points');
            $table->integer('bonus')->nullable();
            $table->decimal('value', 8, 2);
            $table->timestamps();
            $table->softDeletes();
        });

        DB::table('monzy_points')->insert([
            [
                'points' => 100,
                'bonus'  => 0,
                'value'  => 5.00
            ],
            [
                'points' => 359,
                'bonus'  => 50,
                'value'  => 14.50
            ],
            [
                'points' => 1050,
                'bonus'  => 100,
                'value'  => 44.00
            ],
            [
                'points' => 1650,
                'bonus'  => 150,
                'value'  => 72.50
            ]
        ]);
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::drop('monzy_points');
    }
}
