@extends('adminlte::page')
@section('title', 'Daftar Lokasi')

@section('content_header')
    <h1>Daftar Lokasi</h1>
@stop

@section('content')
<div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"></h3>
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            <a href="{{url('locations/create')}}" class="btn btn-default"><i class="fa fa-plus"></i></a>
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="listLocation" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Project</th>
                            <th>Nama Lokasi</th>
                            <th>Provinsi</th>
                            <th>Kota</th>
                            <th>Kecamatan</th>
                            <th>Desa</th>
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
        initDatatable('#listLocation', 'locations');
    </script>
@stop