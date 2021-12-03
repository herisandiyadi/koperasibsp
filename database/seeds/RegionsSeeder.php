<?php

use Illuminate\Database\Seeder;

class RegionsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('ms_regions')->insert([
			[
				'name_area' => 'Area Jawa I',
				'address' => str_random(10)
			],
			[
				'name_area' => 'Area Jawa II',
				'address' => str_random(10)
			],
			[
				'name_area' => 'Area Jawa III',
				'address' => str_random(10)
			],
			[
				'name_area' => 'Area Sumatera',
				'address' => str_random(10)
			],
			[
				'name_area' => 'Kalimantan',
				'address' => str_random(10)
			],
			[
				'name_area' => 'Indonesia Timur',
				'address' => str_random(10)
			]
		]);
	}
}
