@extends('adminlte::page')
@section('title', 'Generate Report Member')
@section('content_header')
    <h1>Generate Report Member</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"></h3>
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            @can('create.generate.report.member')
                                <a href="{{url('generate/member-report/create')}}" class="btn btn-default"><i class="fa fa-plus"></i>    Generate New</a>
                            @endcan
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    @can('view.generate.report.deposit')
                        <table id="dtMemberGenerate" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Nama</th>
                                <th>Start</th>
                                <th>End</th>
                                <th>Status</th>
                                <th width="15%"></th>
                            </tr>
                            </thead>
                            <tbody>
                            </tbody>
                        </table>
                    @endcan
                </div>
            </div>
        </div>
    </div>
@endsection

@section('appjs')
    <script>
        initDatatable('#dtMemberGenerate', 'member-report');
    </script>
@stop
