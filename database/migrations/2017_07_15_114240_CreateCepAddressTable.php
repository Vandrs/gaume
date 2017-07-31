<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

ini_set('memory_limit',0);

class CreateCepAddressTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('faixa_ceps', function(Blueprint $table){
            $table->string('uf',2)->nullable();
            $table->string('localidade',255)->nullable();
            $table->smallInteger('cidade')->nullable();
            $table->string('cep_inicial', 10)->nullable();
            $table->string('cep_final', 10)->nullable();
            $table->string('bairro_nome', 255)->nullable();
            $table->string('cidade_nome', 255)->nullable();
            $table->index('cep_inicial');
            $table->index('cep_final');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('faixa_ceps');
    }
}