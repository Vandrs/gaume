<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TransactionReferenceForeign extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('transactions', function(Blueprint $table) {
            $table->unsignedInteger('transaction_reference_id');
            $table->foreign('transaction_reference_id')
                  ->references('id')
                  ->on('transaction_references');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('transactions', function(Blueprint $table) {
            $table->dropForeign('transactions_transaction_reference_id_foreign');
            $table->dropColumn('transaction_reference_id');
        });
    }
}
