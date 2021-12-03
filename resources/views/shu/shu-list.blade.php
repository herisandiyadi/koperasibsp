@extends('adminlte::page')
@section('title', 'SHU')
@section('content_header')
<h1>SHU</h1>
@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title"></h3>
				<div class="box-tools">
					<div class="input-group input-group-sm" style="width: 150px;">
						@can('create.master.shu')
							<a href="{{url('shu/create')}}" class="btn btn-default"><i class="fa fa-plus"></i>    Tambah SHU</a>
						@endcan
					</div>
				</div>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				@can('view.master.shu')
				<table id="dtShu" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Year</th>
							<th>SHU YHD</th>
							<th>SHU</th>
							<th>Complete SHU</th>
							<th width="20%">Action</th>
						</tr>
					</thead>
					<tbody>

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
    $('#dtShu').DataTable({
		orderCellsTop: true,
		fixedHeader: true,
        stateSave: true,
        responsive: true,
        processing: true,
        serverSide : false,
		scrollX: true,
		autoWidth :false,
        ajax :  '{{url('shu/datatable/all')}}',
        columns: [
            {data: 'year' , name : 'year' },
            {data: 'cadangan_yhd' , name : 'cadangan_yhd' },
            {data: 'shu' , name : 'shu' },
			{data: 'is_complete' , name : 'is_complete' },
			{data: 'action' , name : 'action' },
        ]
    });
</script>
@stop
