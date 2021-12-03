<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TsReportDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('ts_report_details', function (Blueprint $table) {
            $table->increments('id')->unsigned();
            // $table->integer('employee_id')->unsigned();
            $table->integer('report_id')->unsigned();
            $table->decimal('debit',10,2);
            $table->decimal('credit',10,2);
            $table->decimal('rate_value',10,2);
            $table->enum('type', ['loans','deposits']);
            $table->string('loan_name');
            $table->string('deposit_name');
            $table->date('date_transaction');
            $table->integer('status');

            // $table->foreign('employee_id')->references('id')->on('ms_employees');
            $table->foreign('report_id')->references('id')->on('ts_reports');
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
      Schema::dropIfExists('ts_report_details');

    }
}
