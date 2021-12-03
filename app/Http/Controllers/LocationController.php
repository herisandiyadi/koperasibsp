<?php

namespace App\Http\Controllers;

use DB;
use Validator;
use App\Location;
use App\Project;
use App\Province;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;

class LocationController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $selected = Location::get();
        if (request()->ajax()) {
            return DataTables::of($selected)
            ->editColumn('project_id', function ($selected) {
                return  $selected->Project->project_name;
            })
            ->editColumn('province_id', function ($selected) {
                return  $selected->Province->name;
            })
            ->editColumn('city_id', function ($selected) {
                return  $selected->City->name;
            })
            ->editColumn('district_id', function ($selected) {
                return  $selected->District->name;
            })
            ->editColumn('village_id', function ($selected) {
                return  $selected->Village->name;
            })
            ->addColumn('action',function($selected){
                return 
                '<div style="width: 100px; text-align: center">
                <a  class="btn btn-primary btn-sm btnEdit" href="/locations/'.$selected->id.'/edit"><i class="fa fa-edit"></i></a>
                <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="destroyData('."'locations'".','."'".$selected->id."'".','."'". csrf_token() ."'".','."'listLocation'".')">
                <i class="fa fa-trash" data-token="{{ csrf_token() }}"></i></a>
                </div>';
            })
            ->make(true);
        }
        return view('projects.locations.location-list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data ['form']     = array(
            'title'        => 'create',
            'id'           => '',
            'nama_lokasi'  => '',
            'alamat'       => '',
            );
        $data['project_id']= DB::table('ms_projects as p')
                              ->select('p.id as project_id', 'p.project_name')
                              ->leftJoin('ms_locations as l', 'p.id', '=', 'l.project_id')
                              ->whereNull('l.location_name')
                              ->get();
        $data['Province']  =Province::all();
        return view('projects.locations.location-create', $data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData          = $request->validate([
                                        'nama_lokasi'=> 'required|min:3',
                                        'alamat'=> 'required|min:3'
                                    ]);
        $Location                = new Location();
        $Location->project_id    = input::get('project_id');
        $Location->location_name = input::get('nama_lokasi');
        $Location->province_id   = input::get('province_id');
        $Location->city_id       = input::get('city_id');
        $Location->district_id   = input::get('district_id');
        $Location->village_id    = input::get('village_id');
        $Location->address       = input::get('alamat');
        $Location->save();
        \Session::flash('message', 'Data lokasi baru berhasil ditambahkan.');
        return redirect('locations/create');
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $Location     = Location::findOrFail($id);
        $data['form'] = array(
                        'title'        => 'edit',
                        'id'           => $id,
                        'nama_lokasi'  => $Location->location_name,
                        'alamat'       => $Location->address
                        );
        $data['project_id']= DB::table('ms_projects as p')
                        ->select('p.id as project_id', 'p.project_name')
                        ->leftJoin('ms_locations as l', 'p.id', '=', 'l.project_id')
                        ->where('p.id', $Location->project_id)
                        ->first();
  $data['Province']  =Province::all();
  return view('projects.locations.location-create', $data);

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
        $validatedData           = $request->validate([
                                        'nama_lokasi'=> 'required|min:3',
                                        'alamat'=> 'required|min:3'
                                    ]);
        $Location                = Location::where('id', $id)->first();
        $Location->project_id    = input::get('project_id');
        $Location->location_name = input::get('nama_lokasi');
        $Location->province_id   = input::get('province_id');
        $Location->city_id       = input::get('city_id');
        $Location->district_id   = input::get('district_id');
        $Location->village_id    = input::get('village_id');
        $Location->address       = input::get('alamat');
        $Location->save();
        \Session::flash('message', 'Data lokasi baru berhasil diperbaharui.');
        return redirect('locations');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Location::destroy($id)) {
            $data = 'Success';
         }else{
             $data = 'Failed';
         }
         return response()->json($data);
    }
}