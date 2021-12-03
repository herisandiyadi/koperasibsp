<?php
use Illuminate\Database\Seeder;
use Faker\Generator as Faker;

class ProjectsSeeder extends Seeder
{
	/**
	 * Run the database seeds.
	 *
	 * @return void
	 */
	public function run(Faker $faker)
	{
		$dateNow = Now()->format('Y-m-d H:i:s');

		DB::table('ms_projects')->insert([
			[
				'region_code' => 'A-001',
				'project_code' => '1800001',
				'project_name' => 'PT. BANK PERMATA, Tbk',
				'date_salary' => 1,
				'address' => $faker->address,
				'start_date' => $dateNow,
				'end_date' => $dateNow
			],
			[
				'region_code' => 'A-002',
				'project_code' => '1800002',
				'project_name' => 'BANK DANAMON JAKARTA',
				'date_salary' => 1,
				'address' => $faker->address,
				'start_date' => $dateNow,
				'end_date' => $dateNow
			],
			[
				'region_code' => 'A-003',
				'project_code' => '1800003',
				'project_name' => 'BANK MAYBANK INDONESIA, Tbk',
				'date_salary' => 1,
				'address' => $faker->address,
				'start_date' => $dateNow,
				'end_date' => $dateNow
			],
			[
				'region_code' => 'A-004',
				'project_code' => '1800004',
				'project_name' => 'BANK PEMBANGUNAN DAERAH JAWA BARAT DAN BANTEN, Tbk',
				'date_salary' => 1,
				'address' => $faker->address,
				'start_date' => $dateNow,
				'end_date' => $dateNow
			],

			[
				'region_code' => 'A-005',
				'project_code' => '1800005',
				'project_name' => 'PT NISSIN BISCUIT INDONESIA',
				'date_salary' => 1,
				'address' => $faker->address,
				'start_date' => $dateNow,
				'end_date' => $dateNow
			],
			[
				'region_code' => 'A-006',
				'project_code' => '1800006',
				'project_name' => 'CIMB NIAGA AUTO FINANCE',
				'date_salary' => 1,
				'address' => $faker->address,
				'start_date' => $dateNow,
				'end_date' => $dateNow
			],
			[
				'region_code' => 'A-007',
				'project_code' => '1800007',
				'project_name' => 'FEDERAL INTERNATIONAL FINANCE ',
				'date_salary' => 1,
				'address' => $faker->address,
				'start_date' => $dateNow,
				'end_date' => $dateNow
			]

		]);
	}
}
