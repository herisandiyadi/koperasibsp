<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableTotalSimpananMember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('total_deposit_member', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->unsigned();
            $table->integer('ms_deposit_id')->unsigned();
            $table->decimal('value', 10, 2)->unsigned();
            $table->foreign('member_id')->references('id')->on('ms_members');
            $table->foreign('ms_deposit_id')->references('id')->on('ms_deposits');
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
        //
    }
}
