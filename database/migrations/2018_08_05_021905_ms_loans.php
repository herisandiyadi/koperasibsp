<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MsLoans extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ms_loans', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->string('loan_name');
			$table->decimal('rate_of_interest', 10, 2);
			$table->decimal('provisi', 10, 2);
			$table->integer('plafon')->nullable();
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('ms_loans');

	}
}
