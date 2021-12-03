<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MsMenus extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		if (!Schema::hasTable('ms_menus')) {
			Schema::create('ms_menus', function (Blueprint $table) {
				$table->increments('id')->unsigned();
				$table->integer('child_id');
				$table->string('name');
				$table->string('route');
				$table->tinyInteger('sort');
				$table->softDeletes();
				$table->timestamps();
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
		Schema::dropIfExists('ms_menus');
	}
}
