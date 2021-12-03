<?php

namespace App\Http\Controllers;

use App\User;
use App\Member;
use App\MemberPlafon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Input;
use Illuminate\Support\Facades\Schema;
use Yajra\DataTables\Facades\DataTables;

class PlafonController extends GlobalController
{
    public function index()
    {
        $selected = MemberPlafon::getPlafonArea(auth()->user()->region);
//        if(auth()->user()->isPow())
//        {
//            $selected = MemberPlafon::get();
//        }else{
//            $selected = MemberPlafon::fNoSuper()->get();
//        }
        if (request()->ajax()) {
            return DataTables::of($selected)
            ->editColumn('nik_koperasi', function ($selected) {
                return  $selected->member->nik_koperasi;
            })
            ->editColumn('anggota', function ($selected) {
                return $selected->member->first_name;
            })
            ->editColumn('nominal', function ($selected) {
                return  'Rp. '.number_format($selected->nominal, 0, ',', '.');
            })
            ->addColumn('action',function($selected){
                return 
                '<center>
                <a  class="btn btn-primary btn-sm btnEdit" href="/plafons/'.$selected->id.'/edit"><i class="fa fa-edit"></i></a>
                <a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="destroyData('."'plafons'".','."'".$selected->id."'".','."'". csrf_token() ."'".','."'listPlafon'".')">
                <i class="fa fa-trash" data-token="{{ csrf_token() }}"></i></a>
                </center>';
            })
            ->make(true);
        }
        return view('transaction.plafon.plafon-list');
    }
    public function create()
    {
        $data['form']    = array(
                            'title'       => 'create',
                            'id'          => '',
                            'member_id'   => '',
                            'nominal'     => '',
                           );
        $data['member']  = Member::leftJoin('member_plafons', function($join){
                            $join->on('ms_members.id', '=', 'member_plafons.member_id');
                            })
                          ->leftJoin('users', function($join) {
                                 $join->on('users.id', '=', 'ms_members.user_id');
                             })
                           ->select('ms_members.*', 'users.username')
                           ->whereNull('nominal')
                            ->get();
        return view('transaction.plafon.plafon-create', $data);
    }

    public function store(Request $request)
    {
        $input              = input::all();
        $getKey             = $this->getKeysArr($input);
        $plafons            =  new MemberPlafon();
        $plafons->id        = $plafons::max('id')+1;
        $plafons->member_id = $input['member_id'];
        $plafons->nominal   = $this->revive($input['nominal']);
        $plafons->save();
        \Session::flash('success', 'Data baru berhasil ditambahkan.');
            return redirect('plafons/create');
    }

    public function show($id)
    {
        //
    }

    public function edit($id)
    {
        $plafons          = MemberPlafon::findOrFail($id);
        $data['form']     = array(
                            'title'       => 'edit',
                            'id'          => $id,
                            'member_id'   => $plafons->member->first_name.' '.$plafons->member->last_name,
                            'nominal'     => $plafons->nominal,
                          );
        return view('transaction.plafon.plafon-create', $data);
    }

    public function update(Request $request, $id)
    {
        $plafons          = MemberPlafon::where('id', $id)->first();
        $plafons->nominal = $this->revive($request->nominal);
        $plafons->save();
        \Session::flash('message', 'Batas peminjaman anggota  berhasil diperbaharui.');
        return redirect('plafons');
    }

    public function destroy($id)
    {
        if (MemberPlafon::destroy($id)) {
            $data = 'Success';
         }else{
             $data = 'Failed';
         }
         return response()->json($data);
    }
}
