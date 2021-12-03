<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddMoreColumnTsDepositDetail extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ts_deposit_details', function($table) {
			$table->date('payment_date')->nullable()->after('total');
			$table->enum('status', ['unpaid', 'paid', 'pending'])->nullable()->after('total');
			$table->longText('description')->nullable()->after('total');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ts_deposit_details', function($table) {
			$table->dropColumn('payment_date');
			$table->dropColumn('status');
			$table->dropColumn('description');

		});
    }
}
