<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MsDeposits extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ms_deposits', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->string('deposit_name');
			$table->decimal('deposit_minimal', 10, 2);
			$table->decimal('deposit_maximal', 10, 2);
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
		Schema::dropIfExists('ms_deposits');
	}
}
