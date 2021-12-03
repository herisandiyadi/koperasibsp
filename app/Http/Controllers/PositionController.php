<?php

namespace App\Http\Controllers;

use DB;
use App\Position;
use App\Level;
use Validator;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use DataTables;
use Redirect;

class PositionController extends Controller
{
    public function index()
    {
        if(auth()->user()->isPow())
        {
            # dummy
            $selected = Position::whereNotNull('id');
        }else{
            $selected = Position::fNoSuper()->fNoPower();
        }
        if (request()->ajax()) {
            return DataTables::of($selected)
            ->editColumn('level_id', function ($selected) {
                return  $selected->level->name;
            })
            ->addColumn('action',function($selected){
                if (!$selected->level->isPower() && !$selected->level->isSuper())
                {
                    return
                        '<center>
                <a  class="btn btn-primary btn-sm btnEdit" href="/positions/'.$selected->id.'/edit"><i class="fa fa-edit"></i></a>
                <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="destroyData('."'positions'".','."'".$selected->id."'".','."'". csrf_token() ."'".','."'listPosition'".')">
                <i class="fa fa-trash" data-token="{{ csrf_token() }}"></i></a>
                </center>';
                }
                return
                    '<center>
                <a  class="btn btn-primary btn-sm btnEdit" href="/positions/'.$selected->id.'/edit"><i class="fa fa-edit"></i></a>
                </center>';
            })
            ->make(true);
        }
        return view('users.positions.positions-list');
    }

    public function create()
    {
        $data['form'] = array(
                        'title'       => 'create',
                        'id'          => '',
                        'name'        => '',
                        'order_level' => '',
                        'description' => '',
                    );
        $data['level_id'] = Level::fNoSuper()->fNoPower()->get();
        return view('users.positions.positions-create', $data);
    }

    public function store(Request $request)
    {
        $validatedData      = $request->validate([
                                    'name'=> 'required|min:3|unique:positions,name',
//        $validatedData         = $request->validate([
//                                    'name'=> 'required|min:3|unique:levels,name',
                                    'description'=> 'required|min:3,description'
                                ]);
        $findPosition          = Position::where('name', $request->name_area)->orWhere('order_level', $request->order_level)->first();
        if($findPosition){
            \Session::flash('error', 'Nama posisi atau order level sudah tersedia.');
            return redirect('positions/create');
        }else{
            $Position              = new Position();
            $Position->name        = input::get('name');
            $Position->level_id    = input::get('level_id');
            $Position->order_level = input::get('order_level');
            $Position->description = input::get('description');
            $Position->save();
            \Session::flash('message', 'Data posisi baru berhasil ditambahkan.');
            return redirect('positions/create');
        }
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $Position     = Position::findOrFail($id);
        if(!auth()->user()->isPow() && $Position->level->isSuper() && $Position->level->isPower()){
            session()->flash('warning', trans('response-message.unauthorized.visit',['object'=>'Position']));
            return redirect()->back();
        }
        $data['form'] = array(
                        'title'        => 'edit',
                        'id'           => $id,
                        'name'         => $Position->name,
                        'level_id'     => $Position->level_id,
                        'order_level'  => $Position->order_level,
                        'description'  => $Position->description,
                        );
        $data['level_id'] = Level::get();
        return view('users.positions.positions-create', $data);
    }

    public function update(Request $request, $id)
    {
        $validatedData      = $request->validate([
                                    'name'=> 'required|min:3',
                                    'description'=> 'required|min:3,description'
                                ]);
        $findPosition       = Position::where('name', $request->name_area)->orWhere('order_level', $request->order_level)->first();
        if(!auth()->user()->isPow() && $findPosition->level->isSuper() && $findPosition->level->isPower()){
            session()->flash('warning', trans('response-message.unauthorized.visit',['object'=>'Position']));
            return redirect()->back();
        }
        if($findPosition){
            \Session::flash('error', 'Nama posisi atau order level sudah tersedia.');
            return Redirect::back();
        }
        $Position              = Position::where('id', $id)->first();
        $Position->name        = $request->name;
        $Position->order_level = $request->order_level;
        $Position->level_id    = $request->level_id;
        $Position->description = $request->description;
        $Position->save();
        \Session::flash('message', 'Data posisi  berhasil diperbaharui.');
        return redirect('positions');
    }

    public function destroy($id)
    {
        if (Position::destroy($id)) {
            $data = 'Success';
         }else{
             $data = 'Failed';
         }
         return response()->json($data);
    }
}
