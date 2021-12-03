@extends('adminlte::page')
@section('title', 'Daftar Anggota')

@section('content_header')
    <h1>Daftar Anggota</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
				<div class="box-header">
					<div class="col-xs-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <input class="date form-control" type="text" id="start">
                        </div>
					</div>
					<div class="col-xs-3">
                        <div class="input-group">
                            <span class="input-group-addon">
                                <i class="fa fa-calendar"></i>
                            </span>
                            <input class="date form-control" type="text" id="end">
                        </div>
					</div>
					<!-- <div class="col-xs-3">
						<button class="btn btn-primary">Search</button>
					</div> -->
                </div>
                <!-- <div class="box-header">
                    <h3 class="box-title"></h3>
                    <div class="box-tools">
                        {{--<div class="input-group input-group-sm" style="width: 150px;">--}}
                            {{--<a href="{{url('members/create')}}" class="btn btn-default"><i class="fa fa-plus"></i></a>--}}
                        {{--</div>--}}
                    </div>
                </div> -->
                 <!-- /.box-header -->
				 <div class="box-body">
                    <table id="listmember" class="table table-bordered table-hover table-condensed">
                        <thead>
                        <tr>
                            <th width="10%">Nik Koperasi</th>
							<th>Nama</th>
                            <th>Projek</th>
                            <th>Awal PKWT</th>
                            <th>PKWT Berakhir</th>
							<th width="10%">Status</th>
                            <th class="text-center">Deposit</th>
                            <th class="text-center">Loan</th>
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
		var start = $('#start').val();
		var end = $('#end').val();
		$( "#start" ).change(function() {
            initDatatable('#listmember', 'members', '', start, end);
		});

        initDatatable('#listmember', 'members', '', start, end);
    </script>
@stop
