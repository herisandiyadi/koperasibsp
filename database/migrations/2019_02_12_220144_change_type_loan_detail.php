<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeTypeLoanDetail extends Migration
{

    public function up()
    {
        Schema::table('ts_loan_details', function($table) {
            // change column
            DB::statement("alter table ts_loan_details modify loan_number varchar(60) null");
        });

//        if(Schema::hasColumn('ts_loans', 'loan_number')) {
//         Schema::table('ts_loans', function($table) {
//            // drop column
//            $table->dropColumn('loan_number');
//        });
//        }
    }

    public function down()
    {
    }
}
