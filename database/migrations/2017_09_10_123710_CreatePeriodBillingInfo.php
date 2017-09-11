<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePeriodBillingInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('periods', function(Blueprint $table) {
            $table->dropColumn('billed')->nullable(false)->default(false);
            $table->float('points')->nullable(false)->default(0)->after('status');
        });
        
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('periods', function(Blueprint $table) {
            $table->boolean('billed')->nullable(false)->default(false);
            $table->dropColumn('points');
        });
    }
}
