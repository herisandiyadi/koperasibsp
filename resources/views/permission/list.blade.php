@extends('adminlte::page')
@section('title', 'List Permission User')

@section('content_header')
    <h1>List Permission User</h1>
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
{{--						@can('create.master.permissions')--}}
							<a href="{{url('permissions/create')}}" class="btn btn-default"><i class="fa fa-plus"></i> Add Permissions</a>
{{--						@endcan--}}
					</div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="listPermissions" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Nama Permission</th>
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
        initDatatable('#listPermissions', 'permissions');
    </script>
@stop
