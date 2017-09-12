<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use App\Enums\EnumBillingParam;

class CreateBillingParamsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('billing_params', function ($table) {
            $table->increments('id');
            $table->string('code', 20);
            $table->float('value');
            $table->timestamps();
        }); 


        DB::table('billing_params')->insert([
            [
                'code'  => EnumBillingParam::CLASS_POINTS, 
                'value' => 350.0
            ],
            [
                'code'  => EnumBillingParam::CLASS_VALUE, 
                'value' => 10.15
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
        Schema::drop('billing_params');
    }
}
