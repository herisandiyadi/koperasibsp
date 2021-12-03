@extends('adminlte::page')
@section('title', 'Daftar Peminjaman anggota')

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
                            <a href="{{url('loans/create')}}" class="btn btn-default"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="listLoans" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Nama Pinjaman</th>
                            <th>Nilai</th>
                            <th>Provisi</th>
                            <th>Tenor</th>
                            <th>Plafon</th>
                            <th>Lampiran</th>
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
        initDatatable('#listLoans', 'loans');
    </script>
@stop
