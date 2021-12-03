<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
class SuSeederToPermission extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
		$getPermission = Permission::select('id')->get();
		foreach($getPermission as $pId){
			DB::table('role_has_permissions')->insert(
				['permission_id' => $pId['id'], 'role_id' => 2]
			);
		}
    }
}
