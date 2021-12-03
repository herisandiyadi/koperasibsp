<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MsBanks extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ms_banks', function ($table) {
			$table->increments('id')->unsigned();

			$table->integer('member_id')->unsigned();
			$table->string('bank_name');
			$table->string('bank_account_name');
			$table->string('bank_account_number');
			$table->string('bank_branch');
			$table->timestamps();
			$table->softDeletes();
		});

		Schema::table('ms_banks', function ($table) {
			$table->foreign('member_id')->references('id')->on('ms_members');

		});

	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('ms_banks');
	}
}
