<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class PrivilegeController extends Controller
{
    public function __construct()
    {
        $this->middleware(\App\Http\Middleware\TrimStrings::class, ['except' => ['update']]);
    }

    public function view(Request $request, $level)
    {
        ini_set("memory_limit", "10056M");
        $level = Role::findOrFail($level);
        $permissions = Permission::get();
        return view('users.privileges.privilege-view', compact(['level','permissions']));
    }

    public function update(Request $request, Role $level)
    {
        ini_set("memory_limit", "10056M");
        $oldPermissions = $level->getAllPermissions();
        try{
//            $arrPermissionNames = [];
            $arrPermissionNames = array_values($request->except(['_token','permissionTable_length']));
//            foreach ($request->except(['_token','permissionTable_length']) as $key => $part) {
//                $replace = str_replace('_','.',$key);
//                if(substr_count($replace,".")>2){
//                    $lastIndexDot = strrpos($replace,'.');
//                    array_push($arrPermissionNames, substr_replace($replace,'_', $lastIndexDot,1));
//                }
//                else{
//                    array_push($arrPermissionNames, $replace);
//                }
//            }
            $level->syncPermissions($arrPermissionNames);
            session()->flash('success', trans('response-message.success.update',['object'=>'Hak Akses']));
            return redirect()->back();
        }catch (\Exception $e){
            // get back old permission
            $level->syncPermissions($oldPermissions);
            session()->flash('errors', $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    public function addPermission(Request $request, $permitName)
    {
        return Permission::create(['name'=>$permitName]);
    }
}
