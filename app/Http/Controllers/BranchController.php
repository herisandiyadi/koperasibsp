<?php

namespace App\Http\Controllers;

use App\Branch;
use App\Http\Requests\NewBranchRequest;
use App\Http\Requests\UpdateBranchRequest;
use App\Region;
use Carbon\Carbon;
use Illuminate\Http\Request;

class BranchController extends Controller
{
    public function index(Request $request)
    {
        if($request->ajax())
        {
            return $this->datatatable();
        }
        $branches = Branch::get();
        return view('projects.branches.branch-list', compact('branches'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = Region::select(\DB::raw('CONCAT("[",code, "] ", name_area) AS area'),'id')->pluck('area','id')->toArray();
        return view('projects.branches.branch-new', compact('regions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param NewBranchRequest $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Routing\Redirector
     */
    public function store(NewBranchRequest $request)
    {
        $validated = $request->validated();
//        $validated['region_code']= Region::findOrFail($validated['region_id'])->code;
        $branch = Branch::create($request->validated());
        session()->flash('success', trans('response-message.success.create',['object'=>'Cabang']));
        return redirect('branch');
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
        $branch = Branch::findOrFail($id);
        $regions = Region::select(\DB::raw('CONCAT("[",code, "] ", name_area) AS area'),'id')->pluck('area','id')->toArray();
        return view('projects.branches.branch-edit', compact('branch','regions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateBranchRequest $request, $id)
    {
        $validated = $request->validated();
//        $validated['region_code']= Region::findOrFail($validated['region_id'])->code;
        $branch = Branch::findOrFail($id)->update($validated);
        session()->flash('success', trans('response-message.success.update',['object'=>'Cabang']));
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $branch = Branch::whereId($id);
        if($branch->count() < 1)
        {
            return response()->json('Failed');
        }
        $branch->delete();
        return response()->json('Success');
    }

    public function datatatable()
    {
        $branches = Branch::whereNotNull('id');
        return \DataTables::of($branches)
            ->editColumn('address', function($branch){
                return str_limit($branch->address, 15,'...');
            })
            ->editColumn('region_id', function($branch){
                if($branch->region()->count() > 0)
                {
                    return $branch->region->name_area;
                }
                return '';
            })
            ->editColumn('status', function($branch){
                return $branch->status;
            })
            ->addColumn('action', function($branch){
                $deleteUrl = url('branch').'/'.$branch->id;
                $editUrl = url('branch').'/'.$branch->id.'/edit';
                $btnEdit = '';
                $btnDelete = '';
                if(auth()->user()->can('edit.master.branch')){
                    $btnEdit = '<a class="btn btn-primary btn-sm" href="'.$editUrl.'"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete.master.branch')){
                    $clickF = 'destroyData('."'branch'".','."'".$branch->id."'".','."'". csrf_token() ."'".','."'dtBranches'".')';
//                    $btnDelete = '<form method="POST" action="'.$deleteUrl.'" class="form-inline">'.csrf_field().method_field('DELETE').'<button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button> </form>';
                    $btnDelete = '<button class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="'.$clickF.'"><i class="fa fa-trash" data-token="{{ csrf_token() }}"></i></button></button>';
                }
                return $btnEdit.' '.$btnDelete;
            })->make(true);
    }
}
