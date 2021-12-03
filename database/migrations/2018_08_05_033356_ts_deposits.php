<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TsDeposits extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ts_deposits', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->integer('member_id')->unsigned();
			$table->integer('ms_deposit_id')->unsigned();
            $table->enum('type', ['credit', 'debit']);
            $table->enum('deposits_type', ['wajib', 'pokok', 'sukarela', 'lainnya', 'berjangka','shu']);
			$table->decimal('total_deposit', 10, 2)->unsigned();
			$table->dateTime('post_date');
            $table->string('desc')->nullable();
			$table->foreign('member_id')->references('id')->on('ms_members');
			$table->foreign('ms_deposit_id')->references('id')->on('ms_deposits');
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
		Schema::dropIfExists('ts_deposits');
	}
}
