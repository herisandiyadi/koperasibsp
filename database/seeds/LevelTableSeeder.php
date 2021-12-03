<?php

use Illuminate\Database\Seeder;

class LevelTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        foreach (array_values(config('auth.level')) as $level)
        {
            \Spatie\Permission\Models\Role::create(["name"=>$level["name"]]);
//            $apiRole = \Spatie\Permission\Models\Role::create([$level]);
//            $apiRole
        }
    }
}
