@extends('adminlte::page')
@section('title', 'Approval Pinjaman Anggota')

@section('content_header')
    <h1>Approval Pinjaman Anggota</h1>
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
                    <table id="listApprovalLoans" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Nomor Pinjaman</th>
                            <th>Anggota</th>
                            <th>Jenis Pinjaman</th>
							<th>Nilai</th>
                            <th>Start</th>
							<th>End</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="ubahLoanModal" role="dialog">
        <div class="modal-dialog">

            <!-- Modal content-->
            <div class="modal-content">
                <div class="modal-header">
                    <h4 class="modal-title">Ubah Data Pengajuan Pinjaman Anggota</h4>
                    <button type="button" class="close" data-dismiss="modal">&times;</button>
                </div>
                <div class="modal-body">
                    {!! Form::open(['url' => 'update-revisi-loan', 'method' => 'post']) !!}
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Jumlah Pinjaman
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                {!! Form::hidden('loan_id',old('loan_id'), ['class' => 'form-control col-md-7 col-xs-12','id'=> 'loanId','required'=>true]) !!}
                                {!! Form::number('value',old('value'), ['class' => 'form-control col-md-7 col-xs-12','id'=> 'jumlahPinjaman','placeholder'=>'Jumlah Pinjaman','required'=>true]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="clear-fix1"></div><br/>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Biaya Admin
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                {!! Form::number('biaya_admin',old('biaya_admin'), ['class' => 'form-control col-md-7 col-xs-12','id'=> 'jumlahBiayaAdmin','placeholder'=>'Biaya Admin','required'=>true]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="clear-fix1"></div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Biaya Transfer
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-8">
                            <div class="input-group">
                                <span class="input-group-addon">Rp</span>
                                {!! Form::number('biaya_transfer',old('biaya_transfer'), ['class' => 'form-control col-md-7 col-xs-12','id'=> 'jumlahBiayaTransfer','placeholder'=>'Biaya Transfer','required'=>true]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="clear-fix1"></div>
                    <div class="form-group">
                        <label class="control-label col-md-3 col-sm-3 col-xs-4" for="name">Keterangan
                        </label>
                        <div class="col-md-9 col-sm-9 col-xs-8">
                            <div class="input-group">
                                {!! Form::textarea('description',old('description'), ['class' => 'form-control col-md-7 col-xs-12','id'=> 'descriptionPinjaman', 'rows' => 4, 'cols' => 54,'placeholder'=>'Keterangan','required'=>true]) !!}
                            </div>
                        </div>
                    </div>
                    <div class="clear-fix1"></div>
                    <br><br>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary">Update</button>
                    <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                </div>
                {!! Form::close() !!}
            </div>
        </div>
    </div>
@stop
@section('appjs')
    <script>
        initDatatable('#listApprovalLoans', 'persetujuan-pinjaman');
    </script>
@stop
