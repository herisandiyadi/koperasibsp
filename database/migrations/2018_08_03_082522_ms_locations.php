<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MsLocations extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ms_locations', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->integer('project_id')->unsigned();
			$table->string('location_name');
			$table->string('address');
			$table->char('province_id', 2)->nullable();
			$table->char('city_id', 4)->nullable();
			$table->char('district_id', 7)->nullable();
			$table->char('village_id', 10)->nullable();
			$table->timestamps();
			$table->softDeletes();

			$table->foreign('project_id')->references('id')->on('ms_projects');
			$table->foreign('province_id')->references('id')->on('provinces');
			$table->foreign('city_id')->references('id')->on('cities');
			$table->foreign('district_id')->references('id')->on('districts');
			$table->foreign('village_id')->references('id')->on('villages');
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('ms_locations');

	}
}
