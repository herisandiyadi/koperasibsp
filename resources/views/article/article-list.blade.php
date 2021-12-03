@extends('adminlte::page')
@section('title', 'Artikel')
@section('content_header')
<h1>Artikel</h1>
@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title"></h3>
				<div class="box-tools">
					<div class="input-group input-group-sm" style="width: 150px;">
						@can('create.master.article')
							<a href="{{url('article/create')}}" class="btn btn-default"><i class="fa fa-plus"></i>    Tambah Artikel</a>
						@endcan
					</div>
				</div>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				@can('view.master.article')
				<table id="dtArticle" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Tanggal Terbit</th>
							<th>Title</th>
							<th>Tags</th>
							<th>Status</th>
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
    $('#dtArticle').DataTable({
		orderCellsTop: true,
		fixedHeader: true,
        stateSave: true,
        responsive: true,
        processing: true,
        serverSide : false,
		scrollX: true,
		autoWidth :false,
        ajax :  '{{url('article/datatable/all')}}',
        columns: [
            {data: 'published_at' , name : 'published_at' },
            {data: 'title' , name : 'title' },
            {data: 'tags' , name : 'tags' },
            {data: 'status' , name : 'status' },

            {data: 'action' , name : 'action' },
        ]
    });
</script>
@stop
