<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class PencairanSimpananTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::create('pencairan_simpanan', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('member_id')->unsigned();
			$table->foreign('member_id')->references('id')->on('ms_members')->onDelete('cascade');
			$table->integer('bank_id')->unsigned();
			$table->foreign('bank_id')->references('id')->on('ms_banks')->onDelete('cascade');
			$table->date('date');
			$table->text('phone')->nullable();
			$table->text('reason')->nullable();
			$table->decimal('jumlah', 10, 2);
			$table->enum('approval', ['approved', 'rejected', 'waiting'])->default('waiting');
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
		Schema::dropIfExists('pencairan_simpanan');
	}
}
