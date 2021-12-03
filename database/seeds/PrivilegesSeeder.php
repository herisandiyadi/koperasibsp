<?php

use Illuminate\Database\Seeder;

class PrivilegesSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        ini_set("memory_limit", "10056M");
        foreach ( \Spatie\Permission\Models\Role::get() as $role) {
            $role->syncPermissions(config('auth.privileges.'.$role->name));
        }
    }
}
