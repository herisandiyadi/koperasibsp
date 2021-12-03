@extends('adminlte::page')
@section('title', 'List Simpanan')

@section('content_header')
    <h1>List Simpanan</h1>
@stop

@section('content')
<div class="row">
    <div class="col-md-2 col-sm-6 col-lg-2">
        <a href="{{ url('member-detail-deposit/'.$id.'/pokok') }}" style="color: #000;">
            <div class="info-box" style="padding: 10px">
                <!-- Apply any bg-* class to to the icon to color it -->
                <span class="info-box-text">Pokok</span>
                <span class="info-box-number">{{ number_format($totalPokok) }}</span>
                <small>Rupiah</small>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </a>
    </div>
    <div class="col-md-2 col-sm-6 col-lg-2">
        <a href="{{ url('member-detail-deposit/'.$id.'/wajib') }}" style="color: #000;">
            <div class="info-box" style="padding: 10px">
                <!-- Apply any bg-* class to to the icon to color it -->
                <span class="info-box-text">Wajib</span>
                <span class="info-box-number">{{number_format($totalWajib)}}</span>
                <small>Rupiah</small>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </a>
    </div>
    <div class="col-md-2 col-sm-6 col-lg-2">
        <a href="{{ url('member-detail-deposit/'.$id.'/sukarela') }}" style="color: #000;">
            <div class="info-box" style="padding: 10px">
                <!-- Apply any bg-* class to to the icon to color it -->
                <span class="info-box-text">Sukarela</span>
                <span class="info-box-number">{{number_format($totalSukarela)}}</span>
                <small>Rupiah</small>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </a>
    </div>
    <div class="col-md-2 col-sm-6 col-lg-2">
            <div class="info-box" style="padding: 10px">
                <!-- Apply any bg-* class to to the icon to color it -->
                <span class="info-box-text">SHU Ditahan</span>
                <span class="info-box-number">{{number_format($totalShu)}}</span>
                <small>Rupiah</small>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
    </div>
    <div class="col-md-2 col-sm-6 col-lg-2">
        <a href="{{ url('member-detail-deposit/'.$id.'/lainnya') }}" style="color: #000;">
            <div class="info-box" style="padding: 10px">
                <!-- Apply any bg-* class to to the icon to color it -->
                <span class="info-box-text">Lainnya</span>
                <span class="info-box-number">{{number_format($totalLainnya)}}</span>
                <small>Rupiah</small>
                <!-- /.info-box-content -->
            </div>
            <!-- /.info-box -->
        </a>
    </div>
    <div class="col-xs-12">
        <div class="box">
            <div class="box-header">
            </div>
            <!-- /.box-header -->
            <div class="box-body">
                <table id="listDetailDepositMember" class="table table-bordered table-hover">
                    <thead>
                    <tr>
                        <th>Nomor Simpanan</th>
                        <th>Date</th>
                        <th>Type</th>
                        <th>Total</th>
                        <th>Status</th>
                        <th>Keterangan</th>
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
    initDatatable('#listDetailDepositMember', 'member-detail-deposit', {{$id}});
    </script>
@stop
