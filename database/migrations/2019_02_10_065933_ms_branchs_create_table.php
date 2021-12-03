<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MsBranchsCreateTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ms_branchs', function (Blueprint $table) {
			$table->increments('id')->unsigned();
			$table->string('branch_code');
			$table->integer('region_id')->unsigned();
			$table->string('branch_name');
			$table->string('telp');
			$table->string('address')->nullable();
			$table->enum('status', ['Aktif', 'Berakhir'])->nullable();
			$table->foreign('region_id')->references('id')->on('ms_regions');
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
		Schema::dropIfExists('ms_branchs');

	}
}
