@extends('adminlte::page')
@section('title', 'Detail Pinjaman Anggota')
@section('content')
<style>
  a.loaner {
    color: #000;
    text-decoration: none;
  }
  a.loaner:hover {
    color: #000 !important;
    text-decoration: none;
  }
  .info-box-number {
    display: block;
    font-size: 16px;
    margin:5px 0px;
}
.content-header {
    position: relative;
    padding: 0px 0px 15px 15px;
}
  @media print {
      body * {
          visibility: hidden;
      }
      #section-to-print, #section-to-print * {
          visibility: visible;
      }
      #section-to-print {
          position: absolute;
          left: 0;
          top: 0;
      }
  }
#minval {
    border:0;
    background: transparent;
    color:#b9cd6d;
    font-weight:bold;
}
button {
  padding: 5px;
  margin: 0px 5px;
  background: #fff
}
button.selected{
  border:1px solid #00c0ef;
  background: #fff;
}
#installment {
  display: none;
}
#defaultHide {
  display: none;
}
#defaultHide0 {
  display: none;
}
#defaultHide1 {
  display: none;
}
#defaulNo {
  visibility: hidden;
}
</style>

<div id="printArea" class="col-md-12 col-sm-12 col-xs-12">
<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">Detail Pengajuan Pinjaman : {{ $finder->member->full_name }} ({{ $finder->loan_number }})</h3>
  </div>
  <div class="box-body">

        <span>Rincian Pinjaman :</span>
        <p></p>
        <table class="table">
          <tr>
            <td>Jenis Pinjaman <br><small>pinjaman yang dipilih</small></td>
            <td>{{ $finder->ms_loans->loan_name }}</td>
          </tr>
{{--          <tr>--}}
{{--            <td>Cicilan Bulanan <br><small>bunga {{ $finder->rate_of_interest }}%</small></td>--}}
{{--            <td>Rp. <span id="monthlyLoan"></span></td>--}}
{{--          </tr>--}}
          <tr>
            <td>Pinjaman yang diajukan</td>
            <td>Rp. <span id="totalNoRate"></span></td>
          </tr>
		  <tr>
            <td>Sisa Pinjaman</td>
            <td>Rp. {{ number_format($sisa_pinjaman) }}</td>
          </tr>
		  <tr>
			<td>Lama cicilan</td>
			<td>{{ $finder->period }} bulan</td>
		  </tr>
		  <tr>
            <td>Nama proyek</td>
            <td>{{ $finder->member->project->project_name }}</td>
          </tr>
		  <tr>
            <td>PKS proyek</td>
            <td>
			@php
			if($finder->member->project->status == 'Permanent')
			{
				echo 'Permanent';
			}else{
				echo $finder->member->project->start_date .' s/d '. $finder->member->project->end_date;
			}
			@endphp
			</td>
          </tr>
		  <tr>
            <td>Total Pengajuan Pinjaman</td>
            <td>{{ $totalLoan }} x pengajuan</td>
          </tr>
		  <tr>
            <td>Pinjaman terakhir (TERBESAR)</td>
            <td>
			Rp. {{ $lastLoan ? number_format($lastLoan->value) : 0 }}
			</td>
          </tr>
{{--          <tr>--}}
{{--            <td>Nominal yang perlu dibayarkan <br><small>ditambah bunga</small></td>--}}
{{--            <td>Rp. <span id="totalyLoan"></span></td>--}}
{{--          </tr>--}}
{{--          <tr>--}}
{{--            <td>Status <br><small>Pengajuan Pinjaman</small></td>--}}
{{--            <td>--}}
{{--              @if ($finder->approval == null)--}}
{{--                 Menunggu persetujuan dari divisi administrasi.--}}
{{--              @else--}}
{{--                 {{ ucwords($finder->approval) }}--}}
{{--              @endif--}}
{{--                  @if(auth()->user()->isSu() || auth()->user()->isPow())--}}
{{--                      {{ ucwords($finder->approval) }}--}}
{{--                  @else--}}
{{--                      Menunggu persetujuan dari divisi administrasi.--}}
{{--                  @endif--}}
{{--            </td>--}}
{{--          </tr>--}}
          <tr>
            <td>Cicilan Bulanan <br><small>Status Pembayaran</small></td>
            <td>
                <button class="btn btn-primary btn-sm" onclick="installment('{{ \Crypt::encrypt($finder->id) }}')">Tampilkan</button>
                @if($finder->approval == 'direvisi' || $finder->approval == 'disetujui')
                    <button class="btn btn-warning btn-sm" onclick="agreeLoan('{{ \Crypt::encrypt($finder->id) }}')">Setuju</button>
                @endif
              @if(auth()->user()->isSu() || auth()->user()->isPow())
               <button class="btn btn-warning btn-sm" id="defaultHide0" onclick="addTenor('{{ \Crypt::encrypt($finder->id) }}', 'addTenor')" style="color">Tambah Tenor</button>
                <button class="btn btn-primary btn-sm" onclick="printDiv('printArea')" id="defaultHide1">Print</button>

                @endif
            </td>
          </tr>
          @if(auth()->user()->isSu() || auth()->user()->isPow())
          <tr id="defaultHide">
            <td>Perbaharui data <br><small>Pembayaran Pinjaman</small></td>
            <td>
             <button class="btn btn-danger btn-sm" onclick="showAction()">Action</button>
             @if ($finder->approval == 'belum lunas')
               <button class="btn btn-danger btn-sm" onclick="paidAction('{{ \Crypt::encrypt($finder->id) }}', 'lunas')">Lunas</button>
             @else
               <button class="btn btn-danger btn-sm" onclick="paidAction('{{ \Crypt::encrypt($finder->id) }}', 'belum lunas')"> Belum Lunas</button>
             @endif
            </td>
          </tr>
          @endif
        </table>
        {{-- installment loan --}}
        <div id="installment" class="table-responsive">
          <table id="load">
            <thead>
              <td>No. Pinjaman</td>
              <td>Cicilan Bulanan</td>
              <td>Jasa</td>
              <td>Total</td>
              <td>Cicilan Ke -</td>
              <td>Jatuh Tempo</td>
              <td>Tanggal Bayar</td>
              <td>Status</td>
              @if(auth()->user()->isSu() || auth()->user()->isPow())
              <td style="visibility: hidden;" id="visHid">Aksi</td>
              @endif

            </thead>
            <tbody>
            </tbody>
          </table>
        </div>
      </div>
    </div>
  </div>
 </div>
</div>
</div>
{{-- modal data view start--}}
<!-- Modal -->
<div class="modal fade" id="myModal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
 <div class="modal-dialog modal-lg" role="document">
   <div class="modal-content">
    <div class="modal-header">
     <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
      <h4 class="modal-title">Detail Peminjaman Bulan <span id="bulanKe">1</span> </h4>
      </div>
      <div class="modal-body">
       <table class="table">
          <tr>
            <td>Jenis Pinjaman <br><small>pinjaman yang dipilih</small></td>
            <td id="name_of_loan"></td>
          </tr>
          <tr>
            <td>Nominal yang perlu dibayarkan <br><small>ditambah bunga</small></td>
            <td>Rp. <span id="nominalValue"></span></td>
          </tr>
          <tr>
            <td>Status <br><small>Pinjaman bulanan</small></td>
            <td id="status"></td>
          </tr>
          <tr>
            <td>Pilih Pembayaran<br><small>Yang tersedia</small></td>
            <td id="available"></td>
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
@endsection
@section('appjs')
<!-- Javascript -->
<script>
  var lockLoan    = parseInt('{{ $finder->value }}');
  var rateLoan    = lockLoan * ( '{{ $finder->rate_of_interest }}' / 100 );
  var totalLoan   = lockLoan + rateLoan;
  var monthLoan   = parseInt(totalLoan / ('{{ $finder->period  - $finder->add_period }}'));
  // IDR Currency
      monthy      = idr(monthLoan);
      totaly      = idr(totalLoan);
      idrLoan     = idr(lockLoan);
  // start calculate base on pick loan nominal
  $('#minval').html(idrLoan);
  $('#totalNoRate').html(idrLoan);
  $('#monthlyLoan').html(monthy);
  $('#totalyLoan').html(totaly);
  // add tenor record
  function addTenor(e) {
    $('#installment').hide('slow');
    $('#installment').show('slow');
    $('table#load').addClass('table table-bordered table-hovered');
    $('table#load tbody >tr').remove();
    loading('show', 1000);
    $.ajax({
          type        : 'post', // define the type of HTTP verb we want to use (POST for our form)
          url         : '{{url("add-tenor")}}', // the url where we want to POST
          data        : {
                        '_token': '{{ csrf_token() }}',
                        'loan_id': e,
                        'nominal': monthLoan,
                        },
      // using the done promise callback
      success:function(data) {
        if (data.error == 0) {
          // load data
          $.each(data.json, function(key, value){
              jasa = parseInt(value.value) + parseInt(value.value);
              date   = dateTime('dd-mm-yy', value.pay_date);
            if(value.status == '' || value.status == 'belum lunas'){
              status = 'Belum Lunas';
              tgl    = '-';
            }else if(value.status == 'dibatalkan'){
              status = 'Dibatalkan';
              tgl    = '-';

            }else{
              status = 'Lunas';
              tgl    = dateTime('dd-mm-yy', value.updated_at);

            }
            if('{{auth()->user()->isSu() || auth()->user()->isPow()}}'){
              $('table#load').append(
                '<tr>'+
                '<td>'+value.loan_number+'</td>'+
                '<td>Rp '+idr(value.value)+'</td>'+
                '<td>Rp '+idr(value.service)+'</td>'+
                  '<td>Rp '+idr(jasa)+'</td>'+
                '<td>Bulan '+value.in_period+'</td>'+
                '<td class="text-center">'+date+'</td>'+
                '<td class="text-center">'+tgl+'</td>'+
                '<td class="text-center">'+status+'</td>'+
                '<td class="text-center" id="visHid" style="visibility: hidden;"><button class="btn btn-primary" onclick="showRecord('+value.id+')"><i class="ion ion-checkmark-circled"></button></td>'+
                '</tr>'
                );
            } else {
              $('table#load').append(
                '<tr>'+
                '<td>'+value.loan_number+'</td>'+
                '<td>Rp '+idr(value.value)+'</td>'+
                '<td>Rp '+idr(value.service)+'</td>'+
                  '<td>Rp '+idr(jasa)+'</td>'+
                '<td>Bulan '+value.in_period+'</td>'+
                '<td class="text-center">'+date+'</td>'+
                '<td class="text-center">'+tgl+'</td>'+
                '<td class="text-center">'+status+'</td>'+
                '</tr>'
                );
            }
            loading('hide', 1000);
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
  // show or hidden installment
  function installment(e) {
    if('{{auth()->user()->isSu() || auth()->user()->isPow()}}'){
    showTrue();
    }
    if($('#installment').is(':hidden')) {
      $('#installment').show('slow');
      $('table#load').addClass('table table-bordered table-hovered');
      $('table#load tbody >tr').remove();
      // process the data
      loading('show', 1000);
      $.ajax({
          type        : 'post', // define the type of HTTP verb we want to use (POST for our form)
          url         : '{{url("detail-approved")}}', // the url where we want to POST
          data        : {
                        '_token': '{{ csrf_token() }}',
                        'loan_id': e,
                        },
      // using the done promise callback
      success:function(data) {
        if (data.error == 0) {
          // load data
          $.each(data.json, function(key, value){
              jasa = parseInt(value.value) + parseInt(value.service);
              date   = dateTime('dd-mm-yy', value.pay_date);
            if(value.approval == '' || value.approval == 'belum lunas'){
              status = 'Belum Lunas';
              tgl    = '-';
            }else if(value.approval == 'dibatalkan'){
                status = 'Dibatalkan';
                tgl    = '-';

            }else if(value.approval == 'menunggu'){
                status = 'Menunggu';
                tgl    = '-';

            }else if(value.approval == 'ditolak'){
                status = 'Ditolak';
                tgl    = '-';
            }else if(value.approval == 'direvisi'){
                status = 'Direvisi';
                tgl    = '-';
            }else if(value.approval == 'disetujui'){
                status = 'Disetujui';
                tgl    = '-';

            }else{
              status = 'Lunas';
              tgl    = dateTime('dd-mm-yy', value.updated_at);

            }
            if('{{auth()->user()->isSu() || auth()->user()->isPow()}}'){
              $('table#load').append(
                '<tr>'+
                '<td>'+value.loan_number+'</td>'+
                '<td>Rp '+idr(value.value)+'</td>'+
                '<td>Rp '+idr(value.service)+'</td>'+
                  '<td>Rp '+idr(jasa)+'</td>'+
                '<td>Bulan '+value.in_period+'</td>'+
                '<td class="text-center">'+date+'</td>'+
                '<td class="text-center">'+tgl+'</td>'+
                '<td class="text-center">'+status+'</td>'+
                '<td class="text-center" id="visHid" style="visibility: hidden;"><button class="btn btn-primary" onclick="showRecord('+value.id+')"><i class="ion ion-checkmark-circled"></button></td>'+
                '</tr>'
                );
            } else {
              $('table#load').append(
                '<tr>'+
                '<td>'+value.loan_number+'</td>'+
                '<td>Rp '+idr(value.value)+'</td>'+
                '<td>Rp '+idr(value.service)+'</td>'+
                  '<td>Rp '+idr(jasa)+'</td>'+
                '<td>Bulan '+value.in_period+'</td>'+
                '<td class="text-center">'+date+'</td>'+
                '<td class="text-center">'+tgl+'</td>'+
                '<td class="text-center">'+status+'</td>'+
                '</tr>'
                );
            }
            loading('hide', 1000);
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
    } else {
        $('#installment').hide('slow');
        $('table#load tbody >tr').remove();
   }
  }
  // show hidden display
  function showTrue() {
    if($('#defaultHide').is(':hidden')) {
       $('#defaultHide').show('slow');
       $('#defaultHide0').show('slow');
       $('#defaultHide1').show('slow');
    } else{
       $('#defaultHide').hide('slow');
       $('#defaultHide0').hide('slow');
       $('#defaultHide1').hide('slow');
    }
  }
  // show hidden row
  function showAction() {
    if($('table #visHid').css('visibility') == 'visible') {
       $('table #visHid').css('visibility', 'hidden');
    } else{
       $('table #visHid').css('visibility', 'visible');
    }
  }
  // show data record
  function showRecord(e) {
    $('#myModal').modal('show');
    $.ajax({
          type        : 'post', // define the type of HTTP verb we want to use (POST for our form)
          url         : '{{url("add-tenor")}}', // the url where we want to POST
          data        : {
                        '_token': '{{ csrf_token() }}',
                        'loan_id': e,
                        'specific': 'yes',
                        },
      // using the done promise callback
    success:function(data) {
    if (data.error == 0) {
         $('#name_of_loan').html(data.ms_loans.loan_name);
         $('#bulanKe').html(data.json.in_period);
         $('#nominalValue').html(idr(data.json.value));
         status     = data.json.approval;
         status     = status.toLowerCase().replace(/\b[a-z]/g, function(letter) {
                        return letter.toUpperCase();
                    });
         $('#status').html(status);
         if(status  == 'Belum Lunas'){
          var no    = "belum lunas"+","+data.json.id;
          var delay = "ditangguhkan";
           $('#available').html('<button class="btn btn-primary" onclick="actionLoan('+"'lunas'"+","+data.json.id+')">Lunas</button><button class="btn btn-danger" onclick="actionLoan('+"'dibatalkan'"+","+data.json.id+')">Batalkan</button>');
         } else if(status == 'Dibatalkan') {
           $('#available').html('Dibatalkan');
         } else {
           $('#available').html('<button class="btn btn-danger" onclick="actionLoan('+"'belum lunas'"+","+data.json.id+')">Belum Lunas</button>');
         }
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
  function actionLoan(e1, e2) {
    if(e1  == 'dibatalkan'){
      ask  = 'Dibatalkan';
    } else if(e1 == 'belum lunas'){
      ask  = 'Tidak Melunasi'
    } else {
      ask  = 'Melunasi'
    }
    var notice = PNotify.notice({
      title: 'Confirmation Needed',
      text: 'Apakah kamu yakin untuk '+ask+' cicilan yang anda pilih ?',
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
    $.ajax({
          type        : 'post', // define the type of HTTP verb we want to use (POST for our form)
          url         : '{{url("change-status")}}', // the url where we want to POST
          data        : {
                        '_token': '{{ csrf_token() }}',
                        'status': e1,
                        'detail_id': e2,
                        },
      // using the done promise callback
    success:function(data) {
    if (data.error == 0) {
        // load data
         $('#myModal').modal('hide');
        PNotify.success({
                title: 'Success!',
                text: data.msg,
              });
        $('#installment').hide('slow');
        $('#installment').show('slow');
        $('table#load').addClass('table table-bordered table-hovered');
        $('table#load tbody >tr').remove();
        $.each(data.json, function(key, value){
              date   = dateTime('dd-mm-yy', value.pay_date);
            if(value.status == '' || value.status == 'belum lunas'){
              status = 'Belum Lunas';
              tgl    = '-';
            }else if(value.status == 'dibatalkan'){
                status = 'Dibatalkan';
                tgl    = '-';

            }else{
              status = 'Lunas';
              tgl    = dateTime('dd-mm-yy', value.updated_at);

            }
            if('{{auth()->user()->isSu() || auth()->user()->isPow()}}'){
              $('table#load').append(
                '<tr>'+
                '<td>'+value.loan_number+'</td>'+
                '<td>Rp '+idr(value.value)+'</td>'+
                '<td>Bulan '+value.in_period+'</td>'+
                '<td class="text-center">'+date+'</td>'+
                '<td class="text-center">'+tgl+'</td>'+
                '<td class="text-center">'+status+'</td>'+
                '<td class="text-center" id="visHid" style="visibility: hidden;"><button class="btn btn-primary" onclick="showRecord('+value.id+')"><i class="ion ion-checkmark-circled"></button></td>'+
                '</tr>'
                );
            } else {
              $('table#load').append(
                '<tr>'+
                '<td>'+value.loan_number+'</td>'+
                '<td>Rp '+idr(value.value)+'</td>'+
                '<td>Bulan '+value.in_period+'</td>'+
                '<td class="text-center">'+date+'</td>'+
                '<td class="text-center">'+tgl+'</td>'+
                '<td class="text-center">'+status+'</td>'+
                '</tr>'
                );
            }
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
    });
    notice.on('pnotify.cancel', function() {
    });
  }
  function paidAction(el, stat){
    if(stat  == 'lunas'){
        ask  = 'melunasi'
    } else {
        ask  = 'tidak melunasi'
    }
    var notice = PNotify.notice({
      title: 'Confirmation Needed',
      text: 'Apakah Kamu Yakin untuk '+ask+' tagihan anggota?',
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
      loading('show', 1000);
      $.ajax({
          type        : 'post', // define the type of HTTP verb we want to use (POST for our form)
          url         : '{{url("update-status")}}', // the url where we want to POST
          data        : {
                        '_token': '{{ csrf_token() }}',
                        'status': stat,
                        'loan_id': el,
                        },
    // using the done promise callback
    success:function(data) {
      if (data.error == 0) {
          PNotify.success({
              title: 'Success!',
              text: data.msg,
            });
          setTimeout(function(){ window.location.reload(true);; }, 5000);
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
    })
  };
  function printDiv(divName) {
      var printContents = document.getElementById(divName).innerHTML;
      var originalContents = document.body.innerHTML;

      document.body.innerHTML = printContents;

      window.print();

      document.body.innerHTML = originalContents;
  }

  function agreeLoan(e) {
      $.ajax({
          type : 'post', // define the type of HTTP verb we want to use (POST for our form)
          url : '{{url("agree-revisi-loan")}}', // the url where we want to POST
          data : {
              '_token': '{{ csrf_token() }}',
              'loan_id': e,
          },
          // using the done promise callback
          success:function(data) {
              loading('show', 1000);
              PNotify.success({
                  title: 'Pinjaman anda akan di proses.',
                  text: 'Pinjaman anda akan segera di proses.',
                  type: 'success'
              });
              setTimeout(function(){ window.location.reload(true); }, 1000);
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
</script>
@stop
