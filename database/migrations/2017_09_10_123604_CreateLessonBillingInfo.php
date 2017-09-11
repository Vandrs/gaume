<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateLessonBillingInfo extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('lessons', function(Blueprint $table) {
            $table->boolean('billed')->nullable(false)->default(false)->after('status');        
            $table->float('points')->nullable(false)->default(0)->after('billed');
            $table->float('value')->nullable(false)->default(0)->after('points');
            $table->index('billed');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('lessons', function(Blueprint $table) {
            $table->dropColumn('billed');        
            $table->dropColumn('points');
            $table->dropColumn('value');
        });
    }
}
