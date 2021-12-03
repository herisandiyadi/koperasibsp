<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ChangeForeignKeyRegionCodeOnMsProject extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::table('ms_projects', function (Blueprint $table) {
			$table->dropForeign('ms_projects_region_code_foreign');
            $table->dropColumn('region_code');
			$table->integer('region_id')->unsigned()->after('project_code')->nullable();
			$table->foreign('region_id')->references('id')->on('ms_regions');
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
			$table->dropForeign('ms_projects_region_id_foreign');
            $table->dropColumn('region_id');
			$table->string('region_code')->nullable()->after('project_code');
			$table->foreign('region_code')->references('code')->on('ms_regions');
		});
    }
}
