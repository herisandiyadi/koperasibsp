@extends('adminlte::page')
@section('title', 'Daftar Simpanan')

@section('content_header')
    <h1>Detail Simpanan</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <table id="listTsDepositsDetailMembers" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Tipe Simpanan </th>
							<th>No</th>
                            <th class="text-center">Total</th>
                            <th>Status</th>
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
        var el  = '{{$el}}';
        initDatatable('#listTsDepositsDetailMembers', 'member-deposit-list', el);
    </script>
@stop
