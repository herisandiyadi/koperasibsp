<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeDropColumnLoanDetailMember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
//        Schema::table('ts_loans', function($table) {
//            // change column
//            DB::statement("alter table ts_loans modify status enum('tertunda', 'lunas', 'belum lunas', 'ditangguhkan')");
//        });
//        Schema::table('ts_loan_details', function($table) {
//            // change column
//             DB::statement("alter table ts_loan_details modify status enum('tertunda', 'lunas', 'belum lunas', 'ditangguhkan')");
//        });
        Schema::table('ms_members', function($table) {
            // change column
             DB::statement("alter table ms_members modify phone_number varchar(60)");
        });

        Schema::table('ms_banks', function($table) {
            // change column
             DB::statement("alter table ms_banks modify bank_name varchar(60) null, modify bank_account_name varchar(60) null, modify bank_account_number varchar(60) null, modify bank_branch varchar(60) null");
        });

        if(Schema::hasColumn('ms_members', 'id_number')) {
         Schema::table('ms_members', function($table) {
            // drop column
            $table->dropColumn('id_number');
        });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        

    }
}
