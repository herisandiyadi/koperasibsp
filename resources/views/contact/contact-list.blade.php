@extends('adminlte::page')
@section('title', 'Pesan')
@section('content_header')
<h1>Pesan</h1>
@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title"></h3>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
{{--				@can('view.master.contacts')--}}
				<table id="dtcontacts" class="table table-bordered table-hover">
					<thead>
                    <tr>
                        <th>Dari</th>
                        <th>Judul</th>
                        <th>Pesan</th>
                        <th width="15%"></th>
                    </tr>
					</thead>
					<tbody>

					</tbody>
				</table>
{{--				@endcan--}}
			</div>
		</div>
	</div>
</div>
@endsection

@section('appjs')
<script>
    $('#dtcontacts').DataTable({
		orderCellsTop: true,
		fixedHeader: true,
        stateSave: true,
        responsive: true,
        processing: true,
        serverSide : false,
		scrollX: true,
		autoWidth :false,
        ajax :  '{{url('contact/datatable/all')}}',
        columns: [
            {data: 'dari' , name : 'dari' },
            {data: 'judul' , name : 'judul' },
            {data: 'pesan' , name : 'pesan' },
            {data: 'action' , name : 'action' },
        ]
    });
</script>
@stop
