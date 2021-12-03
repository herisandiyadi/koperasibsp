<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class Positions extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('positions')) {
            Schema::create('positions', function (Blueprint $table) {
                $table->increments('id')->unsigned();
                $table->integer('level_id')->unsigned();
                $table->string('name');
                $table->text('description')->nullable(true)->default(null);
                $table->tinyInteger('order_level')->unsigned();
                $table->softDeletes();
                $table->timestamps();
                $table->foreign('level_id')->references('id')->on('levels');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('positions');
    }
}
