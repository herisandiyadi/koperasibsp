@extends('adminlte::page')
@section('title', 'Daftar Proyek')
@section('content_header')
<h1>Daftar Proyek</h1>
@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title"></h3>
				<div class="box-tools">
					<div class="input-group input-group-sm" style="width: 150px;">
						@can('create.master.project')
							<a href="{{url('projects/create')}}" class="btn btn-default"><i class="fa fa-plus"></i>    Tambah Proyek</a>
						@endcan
					</div>
				</div>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				@can('view.master.project')
				<table id="dtProjects" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Nama</th>
							<th>Kode Proyek</th>
							<th>Area</th>
							<th>Alamat</th>
							<th>Tanggal Mulai</th>
							<th>Tanggal Berakhir</th>
                            <th>Status</th>
							<th>Action</th>
						</tr>
					</thead>
					<tbody>
						<!-- {{--@foreach($projects as $project)--}}
						{{--<tr>--}}
							{{--<td>{{$project->project_name}}</td>--}}
							{{--<td>{{$project->region->name_area}}</td>--}}
							{{--<td>{{str_limit($project->address,15,'...')}}--}}
							{{--</td>--}}
							{{--<td>{{$project->start_date->format('d-m-Y')}}</td>--}}
							{{--<td>{{$project->end_date->format('d-m-Y')}}</td>--}}
							{{--<td>--}}
								{{--<a class="btn" href="{{url('projects/'.$project->id.'/edit')}}"><i class="fa fa-edit"></i></a> {!! Form::open(['route'--}}
								{{--=> ['projects.destroy', $project->id], 'method' => 'post','class'=>'form-inline']) !!} {{ method_field('DELETE')--}}
								{{--}}--}}
								{{--<button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button> {!! Form::close() !!}--}}
							{{--</td>--}}
						{{--</tr>--}}
						{{--@endforeach--}} -->
					</tbody>
				</table>
				@endcan
			</div>
		</div>
	</div>
</div>
@endsection

@section('appjs')
<script>
	initDatatable('#dtProjects', 'projects');
    {{--$('#dtProjects').DataTable({--}}
	{{--	orderCellsTop: true,--}}
	{{--	fixedHeader: true,--}}
    {{--    stateSave: true,--}}
    {{--    responsive: true,--}}
    {{--    processing: true,--}}
    {{--    serverSide : false,--}}
	{{--	scrollX: true,--}}
	{{--	autoWidth :false,--}}
    {{--    ajax :  '{{url('projects/datatable/all')}}',--}}
    {{--    columns: [--}}
    {{--        {data: 'project_name' , name : 'project_name' },--}}
    {{--        {data: 'project_code' , name : 'project_code' },--}}
    {{--        {data: 'region_id' , name : 'region_id' },--}}
    {{--        {data: 'address' , name : 'address' },--}}
    {{--        {data: 'start_date' , name : 'start_date' },--}}
    {{--        {data: 'end_date' , name : 'end_date' },--}}
    {{--        {data: 'action' , name : 'action' },--}}
    {{--    ]--}}
    {{--});--}}
</script>
@stop
