<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBiayaBungaBerjalanOnTableMsLoans extends Migration
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
            $table->decimal('biaya_bunga_berjalan', 10, 2)->after('provisi')->default(0);
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
            $table->dropColumn('biaya_bunga_berjalan');
        });
    }
}
