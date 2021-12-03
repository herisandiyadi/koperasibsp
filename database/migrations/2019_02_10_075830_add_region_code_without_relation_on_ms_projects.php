<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddRegionCodeWithoutRelationOnMsProjects extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('ms_projects', function (Blueprint $table) {
			$table->string('region_code')->nullable()->after('region_id');
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('ms_projects', function (Blueprint $table) {
            $table->dropColumn('region_code');
		});
    }
}
