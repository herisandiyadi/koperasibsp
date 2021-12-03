<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableApprovals extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('approvals', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('fk')->unsigned();
            $table->integer('user_id')->unsigned();
            $table->string('model');
            $table->json('approval');
            $table->boolean('is_approve')->default(false);
            $table->boolean('is_reject')->default(false);
            $table->boolean('is_revision')->default(false);
            $table->boolean('is_waiting')->default(false);
            $table->string('status');
            $table->string('note');
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
        Schema::dropIfExists('table_approvals');
    }
}
