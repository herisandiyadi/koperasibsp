<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MsProjects extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ms_projects', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->string('region_code')->nullable();
			$table->string('project_code')->nullable()->unique();
			$table->string('project_name')->nullable();
			$table->string('address')->nullable();
			$table->date('start_date')->nullable();
			$table->date('end_date')->nullable();
			$table->string('contract_number')->nullable();
			$table->string('description')->nullable();
			$table->integer('date_salary')->unsigned();
			$table->integer('total_member')->unsigned()->nullable();
			$table->string('upload')->nullable();
			$table->enum('status', ['Aktif', 'Berakhir', 'Permanent'])->nullable();

			$table->foreign('region_code')->references('code')->on('ms_regions');
			$table->timestamps();
			$table->softDeletes();
		});
	}

	/**
	 * Reverse the migrations.
	 *
	 * @return void
	 */
	public function down()
	{
		Schema::dropIfExists('ms_projects');

	}
}
