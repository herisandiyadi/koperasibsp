<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Privileges extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('privileges')) {
			Schema::create('privileges', function (Blueprint $table) {
				$table->increments('id')->unsigned();
				$table->integer('position_id')->unsigned();
				$table->integer('menu_id')->unsigned();
				$table->boolean('view');
				$table->boolean('edit');
				$table->boolean('delete');
				$table->boolean('create');
				$table->tinyInteger('sort')->unsigned();
				$table->softDeletes();
				$table->timestamps();
				$table->foreign('position_id')->references('id')->on('positions');
				$table->foreign('menu_id')->references('id')->on('ms_menus');
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
		Schema::dropIfExists('privileges');

	}
}
