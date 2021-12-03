<?php

use Illuminate\Database\Seeder;

class DepositsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('ms_deposits')->insert([
			[
				'deposit_name' => 'Simpanan Pokok',
				'deposit_minimal' => 500000,
				'deposit_maximal' => 500000

			],
			[
				'deposit_name' => 'Simpanan Wajib',
				'deposit_minimal' => 500000,
				'deposit_maximal' => 500000

			],
			[
				'deposit_name' => 'Simpanan Sukarela',
				'deposit_minimal' => 500000,
				'deposit_maximal' => 500000

			],
			[
				'deposit_name' => 'Simpanan Berjangka',
				'deposit_minimal' => 500000,
				'deposit_maximal' => 500000
			],
            [
                'deposit_name' => 'SHU Ditahan',
                'deposit_minimal' => 0,
                'deposit_maximal' => 0
            ],
            [
                'deposit_name' => 'Lainnya',
                'deposit_minimal' => 0,
                'deposit_maximal' => 0
            ]
		]);
	}
}
