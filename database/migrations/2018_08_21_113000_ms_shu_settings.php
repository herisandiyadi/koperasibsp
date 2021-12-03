<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class MsShuSettings extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
      Schema::create('ms_shu_settings', function (Blueprint $table) {

        $table->increments('id')->unsigned();
        $table->integer('ts_report_id')->unsigned();
        $table->string('composition_name');
        $table->decimal('shu_previous', 10, 2);
        $table->date('year_previous');
        $table->decimal('total_shu', 10, 2);
        $table->decimal('prosentase', 10, 2);
        $table->date('year');
        $table->timestamps();

        $table->foreign('ts_report_id')->references('id')->on('ts_reports');

      });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
      Schema::dropIfExists('ms_shu_settings');
        
    }
}
