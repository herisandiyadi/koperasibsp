<?php

use Illuminate\Database\Seeder;

class GeneralSettingSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('general_settings')->insert([
            [
                'name' => 'cut-off',
                'content' => '25',
            ],
            [
                'name' => 'generate-potongan',
                'content' => '15',
            ],

        ]);
    }
}
