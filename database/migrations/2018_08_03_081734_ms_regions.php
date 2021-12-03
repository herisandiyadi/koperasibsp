<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MsRegions extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ms_regions', function ($table) {

			// $table->engine = 'InnoDB';

			$table->increments('id')->unsigned();
			$table->string('code')->nullable()->index();
			$table->string('name_area');
			$table->string('telp')->nullable();
			$table->string('alias')->nullable();
			$table->string('address');
			$table->string('active')->nullable();
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
		Schema::dropIfExists('ms_regions');

	}
}
