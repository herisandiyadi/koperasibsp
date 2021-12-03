<?php

namespace App\Http\Controllers;

use DB;
use App\Region;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;

class RegionController extends Controller
{
    public function index()
    {
        $selected = Region::get();
        if (request()->ajax()) {
            return DataTables::of($selected)
            ->addColumn('action',function($selected){
                return 
                '<center>
                <a  class="btn btn-primary btn-sm btnEdit" href="/regions/'.$selected->id.'/edit"><i class="fa fa-edit"></i></a>
                <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="destroyData('."'regions'".','."'".$selected->id."'".','."'". csrf_token() ."'".','."'listRegion'".')">
                <i class="fa fa-trash" data-token="{{ csrf_token() }}"></i></a>
                </center>';
            })
            ->make(true);
        }
        return view('projects.regions.region-list');
    }

    public function create()
    {
        $data    = array(
                    'title'       => 'create',
                    'id'          => '',
                    'name_area'   => '',
                    'address'     => '',
                    );
        return view('projects.regions.region-create', $data);
    }

    public function store(Request $request)
    {
        $validatedData       = $request->validate([
                                'name_area'  => 'required|min:3,name_area',
                                'address'    => 'required|min:3,address'
                               ]);
        $findRegion          = Region::where('name_area', $request->name_area)->first();
        if($findRegion){
        \Session::flash('error', 'Nama area sudah tersedia.');
        return redirect('regions/create');
        }else{
        $Region              = new Region();
        $Region->name_area   = $request->name_area;
        $Region->address     = $request->address;
        $Region->save();
        \Session::flash('message', 'Data area  berhasil ditambahkan.');
        return redirect('regions/create');
        }
    }

    public function show($id)
    {
        //
    }


    public function edit($id)
    {
        $Region = Region::findOrFail($id);
        $data   = array(
                    'title'        => 'edit',
                    'id'           => $id,
                    'name_area'    => $Region->name_area,
                    'address'      => $Region->address,
                    );
        return view('projects.regions.region-create', $data);
    }

    public function update(Request $request, $id)
    {
        $validatedData      = $request->validate([
                                'name_area'  => 'required|min:3,name_area',
                                'address'    => 'required|min:3,address'
                              ]);
        $findRegion         = Region::where(['name_area' => $request->name_area, 'address' => $request->address])->first();
        if($findRegion){
        return redirect('regions');
        }else{
        $level              = Region::where('id', $id)->first();
        $level->name_area   = $request->name_area;
        $level->address     = $request->address;
        $level->save();
        \Session::flash('message', 'Data region  berhasil diperbaharui.');
        return redirect('regions');
        }
    }

    public function destroy($id)
    {
        if (Region::destroy($id)) {
            $data = 'Success';
         }else{
             $data = 'Failed';
         }
         return response()->json($data);
    }
}
