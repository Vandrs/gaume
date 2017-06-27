<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class StudentRegistrationData extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('users', function(Blueprint $table) { 
            $table->string('cpf',11)->nullable(true)->unique()->after('role_id');
            $table->date('birth_date')->nullable(true)->after('password');
            $table->string('nickname',100)->nullable(true)->unique()->after('name');
            $table->string('photo_profile',255)->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function(Blueprint $table) {
            $table->dropColumn('cpf');
            $table->dropColumn('birth_date');
            $table->dropColumn('nickname');
            $table->dropColumn('photo_profile');
        });
    }
}
