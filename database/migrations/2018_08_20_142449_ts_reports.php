<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TsReports extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('ts_reports', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            $table->integer('ts_loan_id')->unsigned();
            $table->integer('ts_deposit_id')->unsigned();

            $table->decimal('total_debit',10,2);
            $table->decimal('total_credit',10,2);
            $table->decimal('total_receivable',10,2);
            $table->enum('type', ['loans','deposits']);
            $table->date('date_report');
            $table->integer('status');
            $table->foreign('ts_loan_id')->references('id')->on('ts_loans');
            $table->foreign('ts_deposit_id')->references('id')->on('ts_deposits');

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
        Schema::dropIfExists('ts_reports');
    }
}
