<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBiayaTransferOnTableMsLoans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //$table->decimal('rate_of_interest', 10, 2);
        Schema::table('ms_loans', function($table) {
            $table->integer('biaya_transfer')->after('provisi')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ms_loans', function($table) {
            $table->dropColumn('biaya_transfer');
        });
    }
}
