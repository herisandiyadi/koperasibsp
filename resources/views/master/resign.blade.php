@extends('adminlte::page')
@section('title', 'Pengajuan pengunduran diri')

@section('content_header')
    {{-- <a href="{{url('resign')}}" class="btn btn-default"><i class="fa fa-arrow-left"></i></a> --}}
    <h1>Pengunduran diri diajukan</h1>
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
                    <table id="subResign" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>#</th>
                            <th>Tanggal</th>
                            <th>Alasan</th>
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
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog modal-lg" role="document">
   <div class="modal-content">
    <div class="modal-header">
     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Detail Simpanan Anggota </h4>
      </div>
      <div class="modal-body">
       <table class="table">
          <tr>
            <td>Tanggal <br><small>Pengajuan pengunduran diri</small></td>
            <td id="date"></td>
          </tr>
          <tr>
            <td>Alasan <br><small></small></td>
            <td><span id="reason"></span></td>
          </tr>
          <tr>
            <td>Status <br><small>Status Pengajuan</small></td>
            <td id="approval"></td>
          </tr>
          <tr>
            <td>Memo <br><small>Catatan saat ada perubahan data</small></td>
            <td id="note"></td>
          </tr>
        </table>
      </div>
      <div class="modal-footer">
      <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
     </div>
    </div>
   </div>
  </div>
{{-- modal data view end--}}
@stop
@section('appjs')
    <script>
    initDatatable('#subResign', 'resign');
    // show data record
    function showRecord(e) {
        console.log(e);
        $('#myModal').modal('show');
        $.ajax({
              type        : 'get',
              url         : '{{url("resign")}}'+'/'+e,
          // using the done promise callback
        success:function(data) {
        if (data.error == 0) {
             $('#date').html(data.json.date);
             $('#note').html(data.json.note);
             $('#reason').html(data.json.reason);
             if(data.json.approval == 'canceled'){
                status = 'Dibatalkan';
             } else if(data.json.approval == 'rejected'){
                status = 'Ditolak';
             } else if(data.json.approval == 'waiting'){
                status = 'Menunggu persetujuan';
             } else if(data.json.approval == 'waiting'){
                status = 'Menunggu persetujuan admin area';
             }else if(data.json.approval == 'approved1'){
                status = 'Menunggu persetujuan admin pusat';
             }else if(data.json.approval == 'approved2'){
                status = 'Pengunduran diri disetujui';
             }
             $('#approval').html(status);
            } else {
              // handling anomali rule
            PNotify.error({
                title: 'Gagal.',
                text: data.msg,
                type: 'error'
            });
          }        
        },
        // handling error code
        error: function (data) {
          PNotify.error({
              title: 'Terjadi anomali.',
              text: 'Mohon hubungi pengembang aplikasi untuk mengatasi masalah ini.',
              type: 'error'
          });
          }
        });
    }
    </script>
@stop
