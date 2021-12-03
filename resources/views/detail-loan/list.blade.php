@extends('adminlte::page')
@section('title', 'List Simpanan')

@section('content_header')
    <h1>List Simpanan</h1>
@stop

@section('content')
<div class="row">
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="listDetailLoanMember" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Jenis Pinjaman</th>
                        <th>Total</th>
                        <th>Start</th>
                        <th>End</th>
                        <th>Periode</th>
                        <th>Periode Saat Ini</th>
                        <th>Status</th>
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
{{-- modal data view end--}}
@stop
@section('appjs')
    <script>
    initDatatable('#listDetailLoanMember', 'member-detail-loan', {{$id}});
    </script>
@stop
