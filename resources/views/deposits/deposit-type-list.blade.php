@extends('adminlte::page')
@section('title', 'Daftar Tipe Simpanan')
@section('content_header')
<h1>Daftar Tipe Simpanan</h1>

@stop
@section('content')
<div class="row">
	<div class="col-xs-12">
		<div class="box">
			<div class="box-header">
				<h3 class="box-title"></h3>
				<div class="box-tools">
					<div class="input-group input-group-sm" style="width: 150px;">
						@can('create.master.transaction-type')
						<a href="{{url('deposits/create')}}" class="btn btn-default"><i class="fa fa-plus"></i>    Tambah</a>
						@endcan
					</div>
				</div>
			</div>
			<!-- /.box-header -->
			<div class="box-body">
				<table id="dtDeposits" class="table table-bordered table-hover">
					<thead>
						<tr>
							<th>Nama</th>
							<th>Min</th>
							<th>Max</th>
							<th width="15%"></th>
						</tr>
					</thead>
					<tbody>
						@foreach ($deposits as $deposit)
						    <tr>
								<td>{{$deposit->deposit_name}}</td>
								<td>Rp {{number_format($deposit->deposit_minimal,0,'.','.')}}</td>
								<td>Rp {{number_format($deposit->deposit_maximal,0,'.','.')}}</td>
								<td>
									@can('edit.master.transaction-type')
									<a class="btn btn-primary" href="{{url('deposits/'.$deposit->id.'/edit')}}"><i class="fa fa-edit"></i></a>
									@endcan
									@can('delete.master.transaction-type')
									{!! Form::open(['route' => ['deposits.destroy', $deposit->id], 'method' => 'post','class'=>'form-inline delete-form','onsubmit'=>'return confirmDelete(e)']) !!}
									{{ method_field('DELETE') }}
									<button class="btn btn-danger" type="submit"><i class="fa fa-trash"></i></button>
									{!! Form::close() !!}</td>
									@endcan
							</tr>
						@endforeach
					</tbody>
				</table>
			</div>
		</div>
	</div>
</div>
@endsection

@section('appjs')
<script>
	$('#dtDeposits').DataTable();
    // $('.delete-form').on('submit',function(e){
    //     console.log(e);
    //     e.preventDefault();
    //     if(!confirm('Do you want to delete this item?')){
    //         e.preventDefault();
    //     }
    // });
    function confirmDelete(e){
        alert(e);
        e.preventDefault();
        return false;
    }
</script>











@stop
