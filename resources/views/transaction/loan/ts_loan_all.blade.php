@extends('adminlte::page')
@section('title', 'Daftar Pinjaman anggota')

@section('content_header')
    <h1>Daftar Pinjaman</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"></h3>
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="listTsLoansAll" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Nomor Pinjaman</th>
                            <th>Anggota</th>
                            <th>Jenis Pinjaman</th>
							<th>Nilai</th>
                            <th>Period</th>
                            <th>In Period</th>
                            <th>Start</th>
							<th>End</th>
                            <th>Status</th>
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
        initDatatable('#listTsLoansAll', 'get-loans');
    </script>
@stop
