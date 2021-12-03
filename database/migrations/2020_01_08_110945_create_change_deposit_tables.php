<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateChangeDepositTables extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('change_deposits', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('member_id')->unsigned();
			$table->foreign('member_id')->references('id')->on('ms_members')->onDelete('cascade');
			$table->date('date');
			$table->text('name')->nullable();
			$table->decimal('last_wajib', 10, 2);
			$table->decimal('last_sukarela', 10, 2);
			$table->decimal('new_wajib', 10, 2);
			$table->decimal('new_sukarela', 10, 2);
			$table->boolean('approval')->default(false);
			$table->timestamps();
		});
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
		Schema::dropIfExists('approval');
	}
}
