<?php

use Illuminate\Database\Seeder;

class PermissionsTableSeeder extends Seeder
{
    /**P
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set("memory_limit", "10056M");
        foreach(config('auth.permissions') as $permit)
        {
            \Spatie\Permission\Models\Permission::create(['name' => 'view.'.$permit]);
            \Spatie\Permission\Models\Permission::create(['name' => 'create.'.$permit]);
            \Spatie\Permission\Models\Permission::create(['name' => 'update.'.$permit]);
            \Spatie\Permission\Models\Permission::create(['name' => 'delete.'.$permit]);
//            \Spatie\Permission\Models\Permission::create(['name' => 'view.'.$permit,'guard_name'=>'api']);
//            \Spatie\Permission\Models\Permission::create(['name' => 'create.'.$permit,'guard_name'=>'api']);
//            \Spatie\Permission\Models\Permission::create(['name' => 'update.'.$permit,'guard_name'=>'api']);
//            \Spatie\Permission\Models\Permission::create(['name' => 'delete.'.$permit,'guard_name'=>'api']);
        }
    }
}
