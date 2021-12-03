@extends('adminlte::page')
@section('title', 'Daftar Simpanan')

@section('content_header')
    <h1>Daftar Simpanan Shu</h1>
@stop

@section('content')
    <div class="row">
        <div class="col-xs-12">
            <div class="box">
                <div class="box-header">
                    <h3 class="box-title"></h3>
                    <div class="box-tools">
                        <div class="input-group input-group-sm" style="width: 150px;">
                            {{-- <a href="{{url('ts-deposits/create')}}" class="btn btn-default"><i class="fa fa-plus"></i></a> --}}
                        </div>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    {{csrf_field()}}
                    <table id="listTsDeposits" class="table table-bordered table-hover">
                        <thead>
                        <tr>
                            <th>Nomor Simpanan</th>
                            <th>Anggota</th>
                            <th>Date</th>
                            <th>Type</th>
                            <th>Total</th>
                            <th>Status</th>
                            <th class="text-center">Action</th>
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
                    <h4 class="modal-title">Detail Simpanan Anggota {{-- <span id="bulanKe">1</span> --}} </h4>
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
                    </table>
                    <table class="table" id="detail_deposit">
                        <thead>
                        <tr>
                            <td>#</td>
                            <td>Tipe Simpanan</td>
                            <td>Total</td>
                        </tr>
                        </thead>
                        <tbody>
                        </tbody>
                    </table>
                    <span id="button"></span>
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
        initDatatable('#listTsDeposits', 'simpanan-shu');
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
                        $('#name_of_deposit').html(data.name_dps);
                        $('#nominalValue').html(idr(data.json.total_deposit));
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
                        var i = 1;
                        $('table#detail_deposit tbody >tr').remove();
                        $.each(data.detail, function(key, value){
                            $('table#detail_deposit').append(
                                '<tr>'+
                                '<td>'+i+'</td>'+
                                '<td>'+value.deposits_type+'</td>'+
                                '<td>Rp. '+idr(value.total)+'</td>'+
                                '</tr>'
                            );
                            i++;
                        });
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
            var notice = PNotify.notice({
                title: 'Confirmation Needed',
                text: 'Apakah kamu yakin untuk memperbaharui status simpanan yang anda pilih ?',
                // icon: 'fas fa-question-circle',
                hide: false,
                stack: {
                    'dir1': 'down',
                    'modal': true,
                    'firstpos1': 25
                },
                modules: {
                    Confirm: {
                        confirm: true
                    },
                    Buttons: {
                        closer: false,
                        sticker: false
                    },
                    History: {
                        history: false
                    },
                }
            });
            notice.on('pnotify.confirm', function() {
                // process the form
                $.ajax({
                    type        : 'POST', // define the type of HTTP verb we want to use (POST for our form)
                    url         : '{{url('update-deposit')}}', // the url where we want to POST
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
                            if(data.status == 'email'){
                                $('#email').val('');
                                $('#password').val('');
                            }else{
                                $('#nik_bsp').val('');
                            }
                        } else {
                            $('#myModal').modal('hide');
                            PNotify.success({
                                title: 'Success!',
                                text: data.msg,
                            });
                            table = $('#listTsDeposits').DataTable();
                            table.ajax.reload(null,false); //reload datatable ajax
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
                // stop the form from submitting the normal way and refreshing the page
            });
        }
    </script>
@stop
