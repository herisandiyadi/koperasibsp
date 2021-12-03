<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class ReplaceTableShu extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
		Schema::dropIfExists('ms_shu_settings');
		Schema::create('ms_shu_settings', function (Blueprint $table) {
			$table->increments('id');
			$table->integer('shu')->unsigned();
			$table->string('shu_name')->default(null)->nullable();
			$table->decimal('cadangan_yhd', 10, 2)->default(0)->nullable();
			$table->decimal('pendidikan', 10, 2)->default(0)->nullable();
			$table->decimal('sosial', 10, 2)->default(0)->nullable();
			$table->decimal('pendiri', 10, 2)->default(0)->nullable();
			$table->decimal('pengurus_pengawas', 10, 2)->default(0)->nullable();
			$table->decimal('pengurus', 10, 2)->default(0)->nullable();
			$table->decimal('ketua1', 10, 2)->default(0)->nullable();
			$table->decimal('sekretaris', 10, 2)->default(0)->nullable();
			$table->decimal('bendahara', 10, 2)->default(0)->nullable();
			$table->decimal('pengawas', 10, 2)->default(0)->nullable();
			$table->decimal('ketua2', 10, 2)->default(0)->nullable();
			$table->decimal('pengawas1', 10, 2)->default(0)->nullable();
			$table->decimal('pengawas2', 10, 2)->default(0)->nullable();
			$table->decimal('anggota', 10, 2)->default(0)->nullable();
			$table->decimal('keanggotaan', 10, 2)->default(0)->nullable();
			$table->decimal('s_wajib', 10, 2)->default(0)->nullable();
			$table->decimal('s_lainnya', 10, 2)->default(0)->nullable();
			$table->decimal('s_sukarela', 10, 2)->default(0)->nullable();
			$table->decimal('jasa_pinjaman', 10, 2)->default(0)->nullable();
			$table->decimal('retail', 10, 2)->default(0)->nullable();
			$table->decimal('senioritas', 10, 2)->default(0)->nullable();
			$table->integer('total_anggota')->unsigned()->default(0)->nullable();
			$table->boolean('is_complete')->default(0)->default(0)->nullable();
			$table->year('shu_year')->default(0)->nullable();

			$table->string('last_shu_name')->default(null)->nullable();
			$table->year('last_shu_year')->default(0)->nullable();
			$table->integer('last_shu')->unsigned()->default(0)->nullable();
			$table->decimal('last_cadangan_partisipasi', 10, 2)->default(0)->nullable();
			$table->decimal('last_pendidikan', 10, 2)->default(0)->nullable();
			$table->decimal('last_sosial', 10, 2)->default(0)->nullable();
			$table->decimal('last_pendiri', 10, 2)->default(0)->nullable();
			$table->decimal('last_pengurus_pengawas', 10, 2)->default(0)->nullable();
			$table->decimal('last_pengurus', 10, 2)->default(0)->nullable();
			$table->decimal('last_ketua1', 10, 2)->default(0)->nullable();
			$table->decimal('last_sekretaris', 10, 2)->default(0)->nullable();
			$table->decimal('last_bendahara', 10, 2)->default(0)->nullable();
			$table->decimal('last_pengawas', 10, 2)->default(0)->nullable();
			$table->decimal('last_ketua2', 10, 2)->default(0)->nullable();
			$table->decimal('last_pengawas1', 10, 2)->default(0)->nullable();
			$table->decimal('last_pengawas2', 10, 2)->default(0)->nullable();
			$table->decimal('last_anggota', 10, 2)->default(0)->nullable();
			$table->decimal('last_keanggotaan', 10, 2)->default(0)->nullable();
			$table->decimal('last_s_wajib', 10, 2)->default(0)->nullable();
			$table->decimal('last_s_lainnya', 10, 2)->default(0)->nullable();
			$table->decimal('last_s_sukarela', 10, 2)->default(0)->nullable();
			$table->decimal('last_jasa_pinjaman', 10, 2)->default(0)->nullable();
			$table->decimal('last_retail', 10, 2)->default(0)->nullable();
			$table->decimal('last_senioritas', 10, 2)->default(0)->nullable();
			$table->integer('last_total_anggota')->unsigned()->default(0)->nullable();

			$table->decimal('cad_shu_anggota', 10, 2)->default(0)->nullable();
			$table->decimal('cad_shu_pendiri', 10, 2)->default(0)->nullable();
			$table->decimal('cad_shu_pengurus', 10, 2)->default(0)->nullable();
			$table->decimal('cad_shu_pengawas', 10, 2)->default(0)->nullable();

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
		Schema::dropIfExists('ms_shu_settings');

	}
}
