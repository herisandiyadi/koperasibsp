<?php

use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (config('auth.positions') as $a)
        {
            DB::table('positions')->insert($a);
        }
    }
}
