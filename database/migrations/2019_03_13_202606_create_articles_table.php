<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateArticlesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('articles', function (Blueprint $table) {
			$table->increments('id');
			$table->string('title');
			$table->longText('content');
			$table->json('tags')->nullable();
			$table->string('author');
			$table->integer('sort')->nullable();
			$table->string('image_name');
			$table->boolean('isShow');
			$table->dateTime('published_at');
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
        Schema::dropIfExists('articles');
    }
}
