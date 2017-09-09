<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MonzyPointName extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('monzy_points', function (Blueprint $table) {
            $table->string('name',100)->nullable(true)->after('id');
        });

        DB::table('monzy_points')->where('id', '=' , 1)->update(['name' => 'Pacote Easy']);
        DB::table('monzy_points')->where('id', '=' , 2)->update(['name' => 'Pacote Medium']);
        DB::table('monzy_points')->where('id', '=' , 3)->update(['name' => 'Pacote Hard']);
        DB::table('monzy_points')->where('id', '=' , 4)->update(['name' => 'Pacote Advanced']);

    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('monzy_points', function (Blueprint $table) {
            $table->dropColumn('name');
        });
    }
}
