<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateMemberPlafonsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('member_plafons', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('member_id')->unsigned();
			$table->integer('nominal')->nullable();

			$table->foreign('member_id')->references('id')->on('ms_members');

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
        Schema::dropIfExists('member_plafons');
    }
}
