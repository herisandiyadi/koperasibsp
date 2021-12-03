<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableResigns extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('resigns', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('member_id')->unsigned();
            $table->foreign('member_id')->references('id')->on('ms_members');
            $table->date('date');
            $table->text('note')->nullable();
            $table->text('reason')->nullable();
            $table->boolean('is_resign')->default(false);
			$table->enum('approval', ['approve','approved1','approved2', 'rejected', 'canceled','waiting'])->default('waiting');
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
        Schema::dropIfExists('resigns');
    }
}
