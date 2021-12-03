<?php

namespace App\Http\Controllers;

use App\Permission;
use App\Region;
use Illuminate\Http\Request;

class PermissionController extends Controller
{
    public function index()
    {
        $selected = Permission::get();
        if (request()->ajax()) {
            return \DataTables::of($selected)
                ->addColumn('action',function($selected){
                    return
                        '<center>
                <a  class="btn btn-primary btn-sm btnEdit" href="/permissions/'.$selected->id.'/edit"><i class="fa fa-edit"></i></a>
                </center>';
                })
                ->make(true);
        }
        return view('permission.list');
    }

    public function create()
    {
        $data    = array(
            'title'       => 'create',
            'id'          => '',
            'name'   => ''
        );
        return view('permission.create', $data);
    }


    public function store(Request $request)
    {
        $find = Permission::where('name', $request->name)->first();
        if($find){
            \Session::flash('error', 'Nama permission sudah tersedia.');
            return redirect('permissions/create');
        } else {
            $Region = new Permission();
            $Region->name = $request->name;
            $Region->guard_name = "web";
            $Region->save();
            \Session::flash('message', 'Data permission  berhasil ditambahkan.');
            return redirect('permissions/create');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $permission = Permission::findOrFail($id);
        $data   = array(
            'title'        => 'edit',
            'id'           => $id,
            'name'    => $permission->name
        );
        return view('permission.create', $data);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
