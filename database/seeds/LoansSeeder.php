<?php

use Illuminate\Database\Seeder;

class LoansSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('ms_loans')->insert([

			[
				'loan_name' => 'Pinjaman Reguler (tunai) ≥ 90%',
				'rate_of_interest' => 1.5,
				'provisi' => 1,
				'plafon' => 90000000,
                'logo' => '1882686712.png',
                'tenor' => 36
			],
			[
				'loan_name' => 'Pinjaman tunai ≤ 90%',
				'rate_of_interest' => 1.2,
				'provisi' => 1,
				'plafon' => 75000000,
                'logo' => '667309079.png',
                'tenor' => 12
			],
			[
				'loan_name' => 'Pinjaman barang',
				'rate_of_interest' => 1.3,
				'provisi' => 1,
				'plafon' => 10000000,
                'logo' => '77175993.png',
                'tenor' => 36
			],
			[
				'loan_name' => 'Pinjaman Pendidikan',
				'rate_of_interest' => 1.3,
				'provisi' => 1,
				'plafon' => 10000000,
                'logo' => '821422127.png',
                'tenor' => 12
			],
			[
				'loan_name' => 'Pinjaman Darurat',
				'rate_of_interest' => 1,
				'provisi' => 1,
				'plafon' => 5000000,
                'logo' => '727181395.png',
                'tenor' => 36
			],
			[
				'loan_name' => 'Pinjaman registrasi tahunan',
				'rate_of_interest' => 1,
				'provisi' => 1,
				'plafon' => 0,
                'logo' => '366188230.png',
                'tenor' => 36
			],
			[
				'loan_name' => 'Weddingloan',
				'rate_of_interest' => 1.5,
				'provisi' => 1,
				'plafon' => 20000000,
                'logo' => '1061802906.png',
                'tenor' => 36
			],
			[
				'loan_name' => 'Travellingloan',
				'rate_of_interest' => 1.5,
				'provisi' => 1,
				'plafon' => 20000000,
                'logo' => '750750179.png',
                'tenor' => 36
			],
			[
				'loan_name' => 'Motorloan',
				'rate_of_interest' => 1.3,
				'provisi' => 1,
				'plafon' => 0,
                'logo' => '685369894.png',
                'tenor' => 36
			],
			[
				'loan_name' => 'Softloan',
				'rate_of_interest' => 1,
				'provisi' => 1,
				'plafon' => 0,
                'logo' => '2059532448.png',
                'tenor' => 36
			],
			[
				'loan_name' => 'Pinjaman Tunai Lain',
				'rate_of_interest' => 1.5,
				'provisi' => 1,
				'plafon' => 100000000,
                'logo' => '1616757432.png',
                'tenor' => 36
			],
			[
				'loan_name' => 'Pinjaman Gada Pratama',
				'rate_of_interest' => 1,
				'provisi' => 1,
				'plafon' => 0,
                'logo' => '2114407217.png',
                'tenor' => 36
			],
			[
				'loan_name' => 'Pinjaman Kendaraan',
				'rate_of_interest' => 1.3,
				'provisi' => 1,
				'plafon' => 250000000,
                'logo' => '1816539824.png',
                'tenor' => 36
			],
			[
				'loan_name' => 'Pinjaman Ibadah Umroh',
				'rate_of_interest' => 0,
				'provisi' => 1,
				'plafon' => 30000000,
                'logo' => '1538701607.png',
                'tenor' => 24
			],
			[
				'loan_name' => 'Pinjaman Bisnis',
				'rate_of_interest' => 1.5,
				'provisi' => 1,
				'plafon' => 300000000,
                'logo' => '1135795859.png',
                'tenor' => 36
			],
			[
				'loan_name' => 'Pinjaman Perseroan',
				'rate_of_interest' => 1,
				'provisi' => 1,
				'plafon' => 1000000000,
                'logo' => '240964594.png',
                'tenor' => 6
			]

		]);
	}
}
