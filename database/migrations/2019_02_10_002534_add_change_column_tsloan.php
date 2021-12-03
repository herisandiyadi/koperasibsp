<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddChangeColumnTsloan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ts_loans', function($table) {
            // add column
            $table->enum('approval', ['disetujui', 'ditolak', 'dibatalkan', 'menunggu', 'lunas', 'belum lunas', 'direvisi'])->nullable()->after('in_period');
            $table->decimal('rate_of_interest', 10, 2)->after('approval')->nullable();
            $table->integer('plafon')->after('rate_of_interest')->nullable();
            // change column
            $table->date('start_date')->nullable()->change();
            $table->date('end_date')->nullable()->change();
            $table->integer('in_period')->nullable()->change();
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
            $table->dropColumn('rate_of_interest');
            $table->dropColumn('approval');
            $table->dropColumn('plafon');

        });
    }
}
