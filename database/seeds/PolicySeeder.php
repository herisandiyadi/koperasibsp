<?php

use Illuminate\Database\Seeder;

class PolicySeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('ms_policy')->insert([
			[
				'name' => 'Pendaftaran Anggota',
				'description' => '<ol>
				<li>Saya setuju pembayarannya dikuasakan kepada HRD PT. BRAVO SATRIA PERKASA melalui pemotongan Gaji.</li>
				<li>Demikian saya bersedia mengikuti/menyepakati seluruh persyaratan dan peraturan serta kebijakan yang berlaku di Koperasi Security &quot;BSP&quot;</li>
			</ol>

			<p><strong>Ketentuan Pinjaman Umum :</strong></p>

			<ol>
				<li>Pinjaman dapat diajukan jika telah aktif sebagai anggota KS&rdquo;BSP&rdquo; minimal 2 bulan / 2 kali potongan Simpanan wajib dan telah melunasi Simpanan Pokok</li>
				<li>Plafond pinjaman provisi, Adm, suku bung,/jasa pinjaman, jangka waktu, dan ketentuan lainnya. Sesuai peraturan dan kebijakan yang berlaku dari KS &ldquo;BSP&rdquo;</li>
				<li>Perhitungan SHU disesuaikan dengan ke-aktifan Anggota KS &ldquo;BSP. SHU hanya diberikan kepada anggota yang tercatat Aktif sebagai Anggota Koperasi Security &ldquo;BSP&rdquo; sampai dengan 31 Desember</li>
			</ol>
			',
			],
			[
				'name' => 'Pinjaman',
				'description' => '<p><strong>Ketentuan &amp; Persyaratan Pinjaman Koperasi Security &quot;BSP&quot;</strong></p>

				<ol>
					<li>Suku bunga disesuaikan dengan suku bunga yang berlaku di Koperasi Security &quot;BSP&quot;.</li>
					<li>Jangka Waktu pinjaman angsuran (sesuai ketentuan/kebijakan yang dipersyaratkan).</li>
					<li>Biaya provisi, administrasi dan premi angsuran sesuai dengan ketentuan/kebijakan yang berlaku</li>
				</ol>

				<p><strong>Syarat -syarat Pinjaman Koperasi Security &quot;BSP&quot;</strong></p>

				<ol>
					<li>Telah menjadi anggota Koperasi Security &quot;BSP&quot; minimal 2 (dua) kali potongan simpanan wajib.</li>
					<li>Telah melunasi simpanan pokok dan simpanan wajib sampai dengan bulan dari tahun pengajuan pinjaman.</li>
					<li>Mengisi form permohonan pinjaman Koperasi Security &ldquo;BSP&rdquo;</li>
					<li>Besarnya plafond pinjaman maksimal (sesuai ketentuan / kebijakan yang berlaku)</li>
					<li>Maksimum potongan Koperasi Security &quot;BSP&quot; (Angsuran pokok + jasa, Simpanan Wajib, Simpanan Sukarela) 35% dari gaji netto.</li>
					<li>Saya setuju Angsuran pinjaman setiap bulan dikuasakan kepada HRD PT. Bravo Satria Perkasa melalui pemotongan gaji</li>
					<li>Peminjam menanggung segala kewajiban yang ditimbulkan atas pinjaman pemohon tersebut di atas apabila peminjam mengalami wanprestasi</li>
					<li>Apabila peminjam meninggal dunia maka hak dan kewajiban pemohon akan diperhitungkan pihak ahli waris</li>
					<li>Menghubungi Koperasi Security &quot;BSP&quot; apabila peminjam dan penjamin dimutasi kerja, pindah alamat, perubahan data, termasuk mengundurkan diri/keluar kerja dari lingkungan PT. Bravo Satria Perkasa</li>
					<li>Bersedia mentaati ketentuan-ketentuan / peraturan-peraturan Koperasi Security &quot;BSP&quot; yang berlaku.</li>
				</ol>
				',
			],
			[
				'name' => 'Pengunduran Diri',
				'description' => '<ol>
				<li>Memberikan wewenang penuh kepada Koperasi Security &quot;BSP&quot; untuk melaksanakan isi pada pasal 6 ayat 1 Anggaran Dasar Koperasi Security &quot;BSP&quot;.</li>
				<li>Menyelesaikan seluruh kewajiban saya apabila masih terdapat outstanding pinjaman.</li>
				<li>Apabila masih terdapat catatan mengenai sisa hak simpanan (pokok, wajib, dan sukarela) maka Koperasi Security &quot;BSP&quot; agar melakukan serah terima kepada saya.</li>
				<li>Apabila pengunduran diri dilakukan diatas tgl 15, maka proses pencairan simpanan Koperasi Security &quot;BSP&quot; paling lambat tgl 10.</li>
				<li>Demikian permohonan ini saya buat, atas bantuan dan kerjasamanya saya ucapkan terimakasih.</li>
			</ol>
			',
			],
			[
				'name' => 'Pengambilan Simpanan Sukarela',
				'description' => 'Demikian form pencairan simpanan sukarela ini saya buat, atas bantuan dan kerjasamanya saya ucapkan terima kasih',
			],
			[
				'name' => 'Perubahan Simpanan',
				'description' => '<ol>
				<li>Saya setuju pembayarannya dikuasakan kepada HRD PT. Bravo Satria Perkasa melalui pemotongan gaji / honor saya setiap bulan&nbsp;</li>
				<li>Demikian saya bersedia mengikuti/menyepakati seluruh persyaratan dan peraturan serta kebijakan yang berlaku di Koperasi Security &quot;BSP&quot;</li>
			</ol>
			',
			]
		]);
	}
}
