<?php

namespace App\Http\Controllers;

use App\Http\Requests\NewProjectRequest;
use App\Http\Requests\UpdateProjectRequest;
use App\Project;
use App\Region;
use App\Branch;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProjectController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $isCanEdit = auth()->user()->can('edit.project.management');
        $isCanDelete = auth()->user()->can('delete.project.management');
        $selected = Project::get();
        if (request()->ajax()) {
            return DataTables::of($selected)
                ->editColumn('project_name', function ($selected) {
                    return  $selected->project_name;
                })
                ->editColumn('project_code', function ($selected) {
                    return  $selected->project_code;
                })
                ->editColumn('region', function ($selected) {
                    return  $selected->region->name_area;
                })
                ->editColumn('address', function ($selected) {
                    return  $selected->address;
                })
                ->editColumn('start_date', function ($selected) {
                    return  $selected->start_date;
                })
                ->editColumn('end_date', function ($selected) {
                    return  $selected->start_date;
                })
                ->editColumn('status', function ($selected) {
                    return  $selected->status;
                })
                ->addColumn('action',function($selected) use ($isCanEdit, $isCanDelete){
                    $btnDelete ='';
                    $btnEdit= '';
                    if($isCanEdit){
                        $btnEdit = '<a  class="btn btn-primary btn-sm btnEdit" href="/projects/'.$selected->id.'/edit"><i class="fa fa-edit"></i></a>';
                    }

                    if($isCanDelete){
                        $btnDelete = '<a class="btn btn-sm btn-danger" href="javascript:void(0)" title="Hapus" onclick="destroyData('."'projects'".','."'".$selected->id."'".','."'". csrf_token() ."'".','."'listProjects'".')">
                                        <i class="fa fa-trash" data-token="{{ csrf_token() }}"></i></a>';
                    }
                    return '<div style="width: 100px; text-align: center">'.$btnEdit.$btnDelete.'</div>';
                })
                ->make(true);
        }
        return view('projects.project-list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $regions = Region::select(\DB::raw('CONCAT("[",code, "] ", name_area) AS area'),'id')->pluck('area','id')->toArray();
        return view('projects.project-new', compact('regions'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(NewProjectRequest $request)
    {
        $validated = $request->validated();
        $projectStart = Carbon::createFromFormat('Y-m-d', $validated['start_date']);
        $projectEnd = Carbon::createFromFormat('Y-m-d', $validated['end_date']);

        if($projectStart->greaterThan($projectEnd))
        {
            session()->flash('errors', collect(['Tanggal Mulai dan Akhir Proyek tidak valid']));
            return redirect()->back()->withInput();
        }
        $validated['region_code']= Region::findOrFail($validated['region_id'])->code;
        $project = Project::create($request->validated());
        session()->flash('success', trans('response-message.success.create',['object'=>'Proyek']));
        return redirect('projects');
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
        $project = Project::findOrFail($id);
        $regions = Region::select(\DB::raw('CONCAT("[",code, "] ", name_area) AS area'),'id')->pluck('area','id')->toArray();
        return view('projects.project-edit', compact('project','regions'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateProjectRequest $request, $id)
    {
        $validated = $request->validated();
        $validated['start_date'] = Carbon::createFromFormat('d-m-Y', $validated['start_date']);
        $validated['end_date'] = Carbon::createFromFormat('d-m-Y', $validated['end_date']);
        if($validated['start_date']->greaterThan($validated['end_date']))
        {
            session()->flash('errors', collect(['Tanggal Mulai dan Akhir Proyek tidak valid']));
            return redirect()->back()->withInput();
        }
        $validated['region_code']= Region::findOrFail($validated['region_id'])->code;
        $project = Project::findOrFail($id)->update($validated);
        session()->flash('success', trans('response-message.success.update',['object'=>'Proyek']));
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
        $project = Project::findOrFail($id);
        $project->delete();
        session()->flash('success', trans('response-message.success.delete', ['object'=>'Proyek']));
        return redirect()->back();
    }

    public function datatable($projects)
    {
        if($projects == 'all')
        {
            $projects = Project::whereNotNull('project_code');
		}
        return \DataTables::of($projects)
            ->editColumn('address', function($project){
                return str_limit($project->address, 15,'...');
            })
            ->editColumn('region_id', function($project){
                if($project->region()->count() > 0)
                {
                    return $project->region->name_area;
                }
                return '';
            })
            ->editColumn('start_date', function($project){
                return Carbon::createFromFormat('Y-m-d', $project->start_date)->format('d-m-Y');
            })
            ->editColumn('end_date', function($project){
                return Carbon::createFromFormat('Y-m-d', $project->end_date)->format('d-m-Y');
            })
            ->addColumn('action', function($project){
                $deleteUrl = url('projects').'/'.$project->id;
                $editUrl = url('projects').'/'.$project->id.'/edit';
                $btnEdit = '';
                $btnDelete = '';
                if(auth()->user()->can('update.master.project')){
                    $btnEdit = '<a class="btn btn-primary" href="'.$editUrl.'"><i class="fa fa-edit"></i></a>';
                }
                if(auth()->user()->can('delete.master.project')){
                    $btnDelete = '<form method="POST" action="'.$deleteUrl.'" class="form-inline">'.csrf_field().method_field('DELETE').'<button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button> </form>';
                }
				return $btnEdit.' '.$btnDelete;
            })->make(true);
	}

	public function getProject(Request $request){
		$project = Project::where('region_id', $request->region_id)
					->where('status', 'Aktif')->get();
		$branch  = Branch::where('region_id', $request->region_id)
				->where('status', 'Aktif')->get();
		$data = array(
			"project" => $project,
			"branch" => $branch
		);
		return $data;
	}
}
