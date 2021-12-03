<?php

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
	/**
	 * Seed the application's database.
	 *
	 * @return void
	 */
	public function run()
	{
		$this->call(
			[
				RegionCsvSeeder::class,
                PermissionsTableSeeder::class,
                LevelTableSeeder::class,
				ProjectCsvSeeder::class,
				LocationsSeeder::class,
				BranchCsvSeeder::class,
				LoansSeeder::class,
				DepositsSeeder::class,
				PolicySeeder::class,
				PositionSeeder::class,
                PowerUser::class,
				SuperSeeder::class,
                PrivilegesSeeder::class,
                GeneralSettingSeeder::class,
			]
		);
	}
}
