<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TsLoans extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ts_loans', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->string('loan_number');
			$table->integer('member_id')->unsigned();
			$table->integer('loan_id')->unsigned();
			$table->decimal('value', 10, 0);
			$table->date('start_date');
			$table->date('end_date');
			$table->integer('period');
			$table->integer('in_period');
            $table->integer('add_period')->default(0)->nullable();
            $table->string('desc')->nullable();
			$table->foreign('member_id')->references('id')->on('ms_members')->onDelete('cascade');
			$table->foreign('loan_id')->references('id')->on('ms_loans')->onDelete('cascade');
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
		Schema::dropIfExists('ts_loans');
	}
}
