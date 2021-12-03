@extends('adminlte::page')
@section('title', 'List Privacy Policy')
@section('content_header')
<h1>Privacy Policy</h1>
@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title"></h3>
				<div class="box-tools">
					<div class="input-group input-group-sm" style="width: 150px;">
						@can('create.master.policy')
							<a href="{{url('policy/create')}}" class="btn btn-default"><i class="fa fa-plus"></i>    Tambah Privacy Policy</a>
						@endcan
					</div>
				</div>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				@can('view.master.policy')
				<table id="dtPolicy" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Nama</th>
							<th width="15%"></th>
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
    $('#dtPolicy').DataTable({
		orderCellsTop: true,
		fixedHeader: true,
        stateSave: true,
        responsive: true,
        processing: true,
        serverSide : false,
		scrollX: true,
		autoWidth :false,
        ajax :  '{{url('policy/datatable/all')}}',
        columns: [
            {data: 'name' , name : 'name' },
            {data: 'action' , name : 'action' },
        ]
    });
</script>
@stop
