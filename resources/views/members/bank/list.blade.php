@extends('adminlte::page')
@section('title', 'Member Bank')
@section('content_header')
    <h1>Member Bank</h1>
@stop
@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">

                <div class="box-body">
                    @can('view.member.bank')
                        <table id="dtMemberBank" class="table table-bordered table-hover">
                            <thead>
                            <tr>
                                <th>Nama Anggota</th>
                                <th>Nama Bank</th>
                                <th>Nama Akun Bank</th>
                                <th>Nomor Akun Bank</th>
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
        initDatatable('#dtMemberBank', 'bank');
    </script>
@stop
