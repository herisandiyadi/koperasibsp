<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnStatusOnDeposit extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ts_deposits', function($table) {
            $table->enum('status', ['unpaid', 'paid', 'pending'])->default('pending')->after('total_deposit');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_deposits', function($table) {
            $table->dropColumn('status');

        });
    }
}
