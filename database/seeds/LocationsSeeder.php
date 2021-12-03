<?php

use Illuminate\Database\Seeder;

class LocationsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run()
	{
		DB::table('ms_locations')->insert([

			[
				'location_name' => 'Bintaro tower',
				'address' => str_random(10),
				'project_id' => 1,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'Matraman',
				'address' => str_random(10),
				'project_id' => 1,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'KCI Surya Kencana Bogor',
				'address' => str_random(10),
				'project_id' => 1,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'KCP Cempaka Mas',
				'address' => str_random(10),
				'project_id' => 1,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],




			[
				'location_name' => 'Ungaran',
				'address' => str_random(10),
				'project_id' => 2,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'Kawasan Candi ',
				'address' => str_random(10),
				'project_id' => 2,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'Semarang',
				'address' => str_random(10),
				'project_id' => 2,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'JL.Projosumarto no.1 wangandawa tegal',
				'address' => str_random(10),
				'project_id' => 2,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],

			[
				'location_name' => 'Surabaya',
				'address' => str_random(10),
				'project_id' => 3,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'Kediri',
				'address' => str_random(10),
				'project_id' => 3,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'BRI Cab. Sampang',
				'address' => str_random(10),
				'project_id' => 3,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'KCI PROBOLINGGO',
				'address' => str_random(10),
				'project_id' => 3,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],


			[
				'location_name' => 'KANTOR OJK',
				'address' => str_random(10),
				'project_id' => 4,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'CAB. BAGAN BATU',
				'address' => str_random(10),
				'project_id' => 4,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'CAB. PALEMBANG',
				'address' => str_random(10),
				'project_id' => 4,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'KC SUNGAILIAT - UNIT CUPAT',
				'address' => str_random(10),
				'project_id' => 4,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],

			[
				'location_name' => 'KCP TANJUNG PURA',
				'address' => str_random(10),
				'project_id' => 5,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'Jl. Pahlawan',
				'address' => str_random(10),
				'project_id' => 5,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'MT. Haryono',
				'address' => str_random(10),
				'project_id' => 5,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'GajaH Mada',
				'address' => str_random(10),
				'project_id' => 5,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],

			[
				'location_name' => 'Cabang Palu',
				'address' => str_random(10),
				'project_id' => 6,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'Cab Makassar',
				'address' => str_random(10),
				'project_id' => 6,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'ARNET SORONG (WITEL PB)',
				'address' => str_random(10),
				'project_id' => 6,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			],
			[
				'location_name' => 'BCA KCP Sorong',
				'address' => str_random(10),
				'project_id' => 6,
				'province_id' => 31,
				'city_id' => 3173,
				'district_id' => 3173050,
				'village_id' => 3173050001
			]

		]);
	}
}
