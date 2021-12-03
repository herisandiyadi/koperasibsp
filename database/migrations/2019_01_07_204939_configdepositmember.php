<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Configdepositmember extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('config_deposit_member', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('member_id')->unsigned();
			$table->enum('type', ['wajib', 'pokok', 'sukarela', 'berjangka', 'lainnya', 'shu'])->nullable();
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
        Schema::dropIfExists('config_deposit_member');
    }
}
