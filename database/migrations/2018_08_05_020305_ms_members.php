<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MsMembers extends Migration
{
	/**
	 * Run the migrations.
	 *
	 * @return void
	 */
	public function up()
	{
		Schema::create('ms_members', function (Blueprint $table) {
			$table->increments('id')->unsigned();
            $table->string('nik_koperasi', 50)->unique()->nullable();
            $table->string('nik_koperasi_lama', 50)->nullable();
            $table->string('nik_bsp', 50)->nullable();
			$table->integer('user_id')->unsigned();
			$table->integer('project_id')->unsigned();
			$table->integer('region_id')->unsigned();
			$table->integer('position_id')->unsigned();
			$table->char('id_number', 25)->nullable();
			$table->string('first_name')->nullable();
			$table->string('last_name')->nullable();
			$table->date('dob')->nullable();
			$table->enum('religion', ['Islam', 'Protestan', 'Katolik', 'Buddha', 'Hindu', 'Konghuchu'])->nullable();
			$table->enum('gender', ['Laki-laki', 'Perempuan'])->nullable();
			$table->string('address')->nullable();
			$table->string('picture')->nullable();
			$table->datetime('join_date')->nullable();
			$table->datetime('start_date')->nullable();
			$table->datetime('end_date')->nullable();
			$table->char('phone_number', 25)->nullable();
			$table->char('email', 150);
			$table->boolean('is_active')->default(0);
            $table->boolean('is_permanent')->default(0);
            $table->string('keterangan')->nullable();
			$table->enum('special', ['user', 'owner'])->nullable();
			$table->dateTime('verified_at')->nullable();
			$table->foreign('project_id')->references('id')->on('ms_projects');
			$table->foreign('region_id')->references('id')->on('ms_regions');
			$table->foreign('user_id')->references('id')->on('users');
			$table->foreign('position_id')->references('id')->on('positions');

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
		Schema::dropIfExists('ms_members');
	}
}
