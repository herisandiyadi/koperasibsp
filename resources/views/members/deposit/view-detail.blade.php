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
                    <table id="listTsDepositsDetail" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Tipe Simpanan </th>
                            <th>No</th>
                            <th class="text-center">Total</th>
                            <th>Status</th>
                            <th>Aksi</th>

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
      <h4 class="modal-title">Detail Simpanan Anggota <span id="bulanKe">1</span> </h4>
      </div>
      <div class="modal-body">
       <table class="table">
          <tr>
            <td>Jenis Simpanan <br><small>yang dipilih anggota</small></td>
            <td id="name_of_deposit"></td>
          </tr>
          <tr>
            <td>Nominal yang perlu dibayarkan <br><small></small></td>
            <td>Rp. <span id="nominalValue"></span></td>
          </tr>
          <tr>
            <td>Status <br><small>Simpanan bulanan</small></td>
            <td id="status"></td>
          </tr>
          <tr>
            <td>Pilih Pembaharuan<br><small>Yang tersedia</small></td>
            <td id="available"></td>
          </tr>
           <tr>
            <td></td>
            <td id="button"></td>
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
      var el  = '{{$el}}';
      initDatatable('#listTsDepositsDetail', 'view-detail', el);
          // show data record
      function showRecord(e) {
        $('#myModal').modal('show');
        $.ajax({
              type        : 'post', // define the type of HTTP verb we want to use (POST for our form)
              url         : '{{url("view-deposit")}}', // the url where we want to POST
              data        : {
                            '_token': '{{ csrf_token() }}',
                            'id': e,
                            },
          // using the done promise callback
        success:function(data) {
        if (data.error == 0) {
             $('#name_of_deposit').html(data.json.deposits_type);
             $('#nominalValue').html(idr(data.json.credit != 0 ? data.json.credit: data.json.debit));
             if(data.json.status == 'pending' || data.json.status == null){
                status = 'Masih Pending';
             } else if(data.json.status == 'paid'){
                status = 'Lunas';
             } else if(data.json.status == 'unpaid'){
                status = 'Belum Lunas';
             }
             $('#status').html(status);
             $('#button').html('<button class="btn btn-warning" onclick="updateRecord('+data.json.id+')">Perbaharui</button>');
             $('#available').html('<form action=""><select name="status" id="status" class="form-control"><option value="unpaid">Belum lunas</option><option value="paid">Lunas</option><option value="pending">Pending</option></select><input type="hidden" name="id" id="id" value="'+data.json.id+'"></form>');
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
          loading('hide', 1000);
          PNotify.error({
              title: 'Terjadi anomali.',
              text: 'Mohon hubungi pengembang aplikasi untuk mengatasi masalah ini.',
              type: 'error'
          });
          }
        });
      }
    function updateRecord(el) {
      var status = $('#status').val();
               // process the form
            $.ajax({
                type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                url         : '{{url('update-detail')}}', // the url where we want to POST
                // data        : {
                //  logo:new FormData($("form")[0])
                // }
                data        : $('form').serialize(),
                // $('form').serialize(), // our data object
                dataType    : 'json', // what type of data do we expect back from the server
                            encode          : true,
                // using the done promise callback
          success:function(data) {
            if(data.error == 1){
                  PNotify.error({
                  title: 'Oh No!',
                  text: data.msg
                });
                $('#myModal').modal('hide');
                  PNotify.success({
                      title: 'Success!',
                      text: data.msg,
                    });
                table = $('#listTsDepositsDetail').DataTable();
                table.ajax.reload(null,false); //reload datatable ajax
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
            // stop the form from submitting the normal way and refreshing the page
            e.preventDefault();
    }
    </script>
@stop
