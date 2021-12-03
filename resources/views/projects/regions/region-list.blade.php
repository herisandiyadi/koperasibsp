@extends('adminlte::page')
@section('title', 'Daftar Area')

@section('content_header')
    <h1>Daftar Area</h1>
@stop

@section('content')
<div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
{{--					<div class="col-xs-3">--}}
{{--						<input class="date form-control" type="text">--}}
{{--					</div>--}}
{{--					<div class="col-xs-3">--}}
{{--						<input class="date form-control" type="text">--}}
{{--					</div>--}}
{{--					<div class="col-xs-3">--}}
{{--						<button class="btn btn-primary">Search</button>--}}
{{--					</div>--}}
					<div class="col-xs-3 pull-right text-right">
						@can('create.master.area')
							<a href="{{url('regions/create')}}" class="btn btn-default"><i class="fa fa-plus"></i> Add Area</a>
						@endcan
					</div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="listRegion" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Nama Area</th>
                            <th>Alamat</th>
                            <th class="text-center">Aksi</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
@stop
@section('appjs')
    <script>
        initDatatable('#listRegion', 'regions');
    </script>
@stop
