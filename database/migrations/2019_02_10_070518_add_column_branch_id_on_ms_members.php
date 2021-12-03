<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddColumnBranchIdOnMsMembers extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ms_members', function($table) {
			$table->integer('branch_id')->unsigned()->after('region_id')->nullable();
			$table->foreign('branch_id')->references('id')->on('ms_branchs');

        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ms_members', function($table) {
            $table->dropColumn('branch_id');

        });
    }
}
