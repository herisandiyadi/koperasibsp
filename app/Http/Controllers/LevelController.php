<?php

namespace App\Http\Controllers;

use App\User;
use DB;
use App\Level;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;

class LevelController extends Controller
{
    public function index()
    {
        if(auth()->user()->isPow())
        {
            $selected = Level::get();
        }else{
            $selected = Level::fNoSuper()->get();
        }
        if (request()->ajax()) {
            return DataTables::of($selected)
            ->editColumn('count', function ($selected) {
                return $selected->countUser();
            })
            ->addColumn('action',function($selected){
                $isCanEdit = auth()->user()->can('edit.auth.level');
                $isCanDelete = auth()->user()->can('delete.auth.level');
                $btnDelete ='';
                $btnEdit= '';
                $btnPrivilege='';
                if(auth()->user()->isPow())
                {
                    $btnPrivilege ='<a class="btn btn-sm btn-warning" href="'.url("privilege/".$selected->id).'/view"><i class="fa fa-shield"></i></a>';
                }
                if($isCanEdit){
                    $btnEdit = '<a class="btn btn-primary btn-sm btnEdit" href="'.url("levels/".$selected->id).'/edit"><i class="fa fa-edit"></i></a>';
                }
                if($isCanDelete){
                    $btnDelete = '<button class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="destroyData('."'levels'".','."'".$selected->id."'".','."'". csrf_token() ."'".','."'listLevel'".')"><i class="fa fa-trash" data-token="{{ csrf_token() }}"></i></button>';
                }

                return '<center>'.$btnPrivilege.$btnEdit.$btnDelete.'</center>';
            })
            ->make(true);
        }
        return view('users.levels.levels-list');
    }

    public function create()
    {
        $data = array(
            'title'       => 'create',
            'id'          => '',
            'name'        => '',
            'description' => '',
        );
        return view('users.levels.levels-create', $data);
    }

    public function store(Request $request)
    {
        $validatedData      = $request->validate([
                                'name'=> 'required|min:3|unique:levels,name',
                                'description'=> 'required|min:3,description'
                            ]);
        $findLevel          = DB::table('levels')->where('name', $request->name)->count();
        if($findLevel   > 0){
            \Session::flash('error', 'Nama level sudah dipakai.');
            return redirect('levels/create');
        }else{
        $level              = new Level();
        $level->name        = input::get('name');
        $level->description = input::get('description');
        $level->save();
            \Session::flash('success', 'Data level baru berhasil ditambahkan.');
            return redirect('levels/create');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $level = Level::findOrFail($id);
        if(!auth()->user()->isPow() && $level->name == config('auth.level.SUPERADMIN.name')){
            session()->flash('warning', trans('response-message.unauthorized.visit'));
            return redirect()->back();
        }
        $data  = array(
                    'title'        => 'edit',
                    'id'           => $id,
                    'name'         => $level->name,
                    'description'  => $level->description,
                    );
        return view('users.levels.levels-create', $data);
    }

    public function update(request $request, $id)
    {
        $validatedData      = $request->validate([
                                    'name'       => 'required|min:3,name',
                                    'description'=> 'required|min:3,description'
                                ]);
        $findLevel          = DB::table('levels')->where(['name' => $request->name, 'description' => $request->description])->first();
        if($findLevel){
        \Session::flash('error', 'Nama level sudah dipakai.');
        return redirect()->back();
        }else{
        $level              = Level::where('id', $id)->first();
        $level->name        = $request->name;
        $level->description = $request->description;
        $level->save();
        \Session::flash('message', 'Data level  berhasil diperbaharui.');
        return redirect('levels');
        }
    }

    public function destroy($id)
    {
        $level = Level::findOrFail($id);
        if($level->position()->count() > 0)
        {
            $data = 'Failed';
            return response()->json($data);
        }
        if($level->name == config('auth.level.SUPERADMIN.name')){
            $data = 'Failed';
            return response()->json($data);
        }
        if (Level::destroy($id)) {
            $data = 'Success';
         }else{
             $data = 'Failed';
         }
         return response()->json($data);
    }
}
