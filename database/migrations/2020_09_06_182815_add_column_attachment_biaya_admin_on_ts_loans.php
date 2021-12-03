<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnAttachmentBiayaAdminOnTsLoans extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ts_loans', function($table) {
            $table->string('attachment')->nullable()->after('value')->default(null);
            $table->integer('biaya_admin')->after('value')->default(0);
            $table->integer('biaya_provisi')->after('value')->default(0);
            $table->integer('biaya_bunga_berjalan')->after('value')->default(0);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_loans', function($table) {
            $table->dropColumn('attachment');
            $table->dropColumn('biaya_admin');
            $table->dropColumn('biaya_provisi');
            $table->dropColumn('biaya_bunga_berjalan');
        });
    }
}
