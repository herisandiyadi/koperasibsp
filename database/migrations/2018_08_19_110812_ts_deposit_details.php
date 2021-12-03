<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TsDepositDetails extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ts_deposit_details', function (Blueprint $table) {

			$table->increments('id')->unsigned();
			$table->integer('transaction_id')->unsigned();
			$table->enum('deposits_type', ['wajib', 'pokok', 'sukarela', 'lainnya', 'berjangka','shu']);
			$table->decimal('debit', 10, 2);
			$table->decimal('credit', 10, 2);
			$table->decimal('total', 10, 2);
			$table->foreign('transaction_id')->references('id')->on('ts_deposits');

			$table->timestamps();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('ts_deposit_details');

	}
}
