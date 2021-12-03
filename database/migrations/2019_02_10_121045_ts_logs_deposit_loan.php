<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TsLogsDepositLoan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ts_logs_deposit_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('name', 50);
            $table->integer('member_id')->unsigned();
            $table->decimal('nominal_in', 10, 0)->nullable();
            $table->decimal('nominal_out', 10, 0)->nullable();
            $table->decimal('last_nominal', 10, 0)->nullable();
            $table->foreign('member_id')->references('id')->on('ms_members')->onDelete('cascade');
            $table->timestamps();
        });
        Schema::create('ts_logs_loan_histories', function (Blueprint $table) {
            $table->increments('id');
            $table->date('date');
            $table->string('name', 50);
            $table->integer('member_id')->unsigned();
            $table->decimal('nominal_in', 10, 0)->nullable();
            $table->decimal('nominal_out', 10, 0)->nullable();
            $table->decimal('last_nominal', 10, 0)->nullable();
            $table->foreign('member_id')->references('id')->on('ms_members')->onDelete('cascade');
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
        Schema::dropIfExists('ts_logs_loan_histories');
        Schema::dropIfExists('ts_logs_deposit_histories');
    }
}
