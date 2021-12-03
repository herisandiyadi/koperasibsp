<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TsLoanDetails extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('ts_loan_details', function (Blueprint $table) {

        $table->increments('id')->unsigned();
        $table->integer('loan_id')->unsigned();
        $table->integer('loan_number');
        $table->decimal('value', 10, 0);
	  	$table->decimal('service', 10, 0)->default(0);
	  	$table->date('pay_date');
        $table->integer('in_period');
        $table->enum('approval', ['disetujui', 'ditolak', 'dibatalkan', 'menunggu', 'lunas', 'belum lunas', 'direvisi'])->default('menunggu')->nullable();
        $table->foreign('loan_id')->references('id')->on('ts_loans');

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
      Schema::dropIfExists('ts_loan_details');

    }
}
