@extends('adminlte::page')
@section('title', 'Daftar Level')

@section('content_header')
    <h1>Daftar Position</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"></h3>
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            @can('create.master.position')
                                <a href="{{url('positions/create')}}" class="btn btn-default"><i class="fa fa-plus"></i></a>
                            @endcan
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="listPosition" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Nama</th>
                            <th>Level</th>
                            <th>Deskripsi</th>
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
        initDatatable('#listPosition', 'positions');
    </script>
@stop