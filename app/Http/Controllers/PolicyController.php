<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Policy;

class PolicyController extends Controller
{
	/**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $policys = Policy::get();
        return view('policy.policy-list', compact('policys'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('policy.policy-create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
		$privacy = new Policy();
		$privacy->name = $request->name;
		$privacy->description = $request->description;
		$privacy->save();

        session()->flash('success', trans('response-message.success.create',['object'=>'Privacy Policy']));
        return redirect('policy');
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\GeneralSetting  $generalSetting
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\GeneralSetting  $generalSetting
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $policy = Policy::findOrFail($id);
        return view('policy.policy-edit', compact('policy'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\GeneralSetting  $generalSetting
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
		$policy = Policy::findOrFail($id);
		$policy->name = $request->name;
		$policy->description = $request->description;
		$policy->update();

        session()->flash('success', trans('response-message.success.update',['object'=>'Privacy Policy']));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\GeneralSetting  $generalSetting
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        if (Policy::destroy($id)) {
            $data = 'Success';
         }else{
             $data = 'Failed';
         }
        session()->flash('success', trans('response-message.success.delete',['object'=>'Privacy Policy']));
        return redirect('policy');
	}

	public function datatable($policies)
    {
        if($policies == 'all')
        {
            $policies = Policy::all();
		}
        return \DataTables::of($policies)
            ->editColumn('name', function($policy){
                return $policy->name;
            })

            ->addColumn('action', function($policy){
                $deleteUrl = url('policy').'/'.$policy->id;
                $editUrl = url('policy').'/'.$policy->id.'/edit';
                $btnEdit = '';
                $btnDelete = '';
                if(auth()->user()->can('edit.master.policy')){
                    $btnEdit = '<a class="btn btn-primary" href="'.$editUrl.'"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete.master.policy')){
                    $btnDelete = '<form method="POST" action="'.$deleteUrl.'" class="form-inline">'.csrf_field().method_field('DELETE').'<button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button> </form>';
                }
				return $btnEdit.' '.$btnDelete;
            })->make(true);
	}
}
