<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateUsersTable extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('users', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->string('name');
			$table->string('email')->unique();
			$table->string('username')->unique();
			$table->integer('position_id')->unsigned();
            $table->integer('region_id')->unsigned()->nullable();
			$table->string('password');
			$table->rememberToken();
			$table->timestamps();
            $table->foreign('region_id')->references('id')->on('ms_regions');
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
		Schema::dropIfExists('users');
	}
}
