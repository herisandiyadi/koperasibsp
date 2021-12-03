@extends('adminlte::page')
@section('title', 'Daftar Simpanan')

@section('content_header')
    <h1>Daftar Simpanan</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"></h3>
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <a href="{{url('ts-deposits/create')}}" class="btn btn-default"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="listTsDeposits" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Nomor Simpanan</th>
                            <th>Anggota</th>
							<th>Total</th>
                            <th class="text-center">Action</th>
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
        initDatatablePost('#listTsDeposits', 'ts-deposits');
    </script>
@stop
