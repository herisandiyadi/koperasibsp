@extends('adminlte::page')
@section('title', 'Ajukan Pinjaman')
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
#pickLoan {
  margin: 0px 8px;
}
hr {
  margin-top: 10px;
}
</style>

<div class="col-md-12 col-sm-12 col-xs-12">
<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">Pinjaman Pribadi :</h3><br>
      <span class="labelTop">Dapatkan cicilan pinjaman hingga batas maksimum pinjaman {{ number_format($findLoan->plafon) }} </span>
  </div>
  <div class="box-body">
	<span class="labelTop"><b>Nama proyek</b></span>
	<div>
	{{ $getMember->project->project_name }}
	</div>
  </div>
  <div class="box-body">
	<span class="labelTop"><b>PKS proyek</b></span>
	<div>
	@php
	if($getMember->project->status == 'Permanent')
	{
		echo 'Permanent';
	}else{
		echo $getMember->project->start_date .' s/d '. $getMember->project->end_date;
	}
	@endphp
	</div>
  </div>

  <div class="box-body">
      <span class="labelTop">Pilih Penjamin Pinjaman Anda</span>
      <div class="text-center" style="margin-top: 10px; margin-bottom: 10px;">
          <select id="penjamin" name="penjamin" class="form-control">
              @foreach ($penjamin as $p)
                  <option value="{{ $p->id }}">{{ $p->name }}</option>
              @endforeach
          </select>
      </div>
    <div class="col-md-12 no-padding">
      <span class="pull-left">Lama cicilan :</span>
      <br>
      @php
        $arr_month = [];
      @endphp
        @php

            $arr_month    = $tenors;

        @endphp

      <div class="text-center" style="margin-top: 10px; margin-bottom: 10px;">
            <select id="tenor" class="form-control">
                @foreach ($arr_month as $el)
                <option value="{{ $el }}">{{ $el }} Bulan</option>
                @endforeach
            </select>
      </div>
        @if($findLoan->attachment)
        <span class="labelTop">Lampiran</span>
        <div class="text-center" style="margin-top: 10px; margin-bottom: 10px;">
                <input type="file" id="images" name="images" class="form-control"/>
        </div>
        @endif
      <p>
         <label>Nominal : Rp. </label>
         <input type="text" id="minval" disabled>
         <input type="hidden" id="lockLoan">
         <div id="pickLoan"></div>
      </p>
      <div id="showloan" style="display: none">
        <span>Rincian Pinjaman :</span>
        <p></p>
        <table class="table">
          <tr>
            <td>Jenis Pinjaman <br><small>pinjaman yang dipilih</small></td>
            <td align="right"><b>{{ $findLoan->loan_name }}</b></td>
          </tr>
            <tr>
                <td><b>Keterangan Jenis Pinjaman</b><br>
                    {!! $findLoan->description !!}</td>
                <td align="right"></td>
            </tr>
          <tr>
            <td>Jasa x Lama Cicilan <br><small>jasa {{ $findLoan->rate_of_interest }}%</small></td>
            <td align="right">Rp. <span id="monthlyLoan"></span></td>
          </tr>
          <tr>
            <td>Jumlah Pinjaman</td>
            <td align="right">Rp. <span id="totalNoRate"></span></td>
          </tr>
          <tr>
              <td><b>Total Pinjaman</td>
              <td align="right"><b>Rp. <span id="totalyLoan"></span></b></td>
          </tr>
            <tr>
                <td><b>Cicilan Bulanan</td>
                <td align="right"><b>Rp. <span id="bulananLoan"></span></b></td>
            </tr>
            <tr>
                <td>Biaya Admin</td>
                <td align="right">Rp. <span id="biayaAdmin"></span></td>
            </tr>
            <tr>
                <td>Biaya Transfer</td>
                <td align="right">Rp. <span id="biayaTransfer"></span></td>
            </tr>
            <tr>
                <td>Biaya Provisi</td>
                <td align="right">Rp. <span id="biayaProvisi"></span></td>
            </tr>
            <tr>
                <td>Jasa Berjalan<br><small>bunga {{ $findLoan->biaya_bunga_berjalan }}%</small></td>
                <td align="right">Rp. <span id="biayaBungaBerjalan"></span></td>
            </tr>
            <tr>
                <td><b>Pinjaman yang akan diterima <br>
                <small> <span style="color: red;">*</span> dikurangi biaya admin, provisi dan ( jasa berjalan jika persetujuan diatas tanggal 14 tiap bulanya ).</small></b>
                    <br>
                    <small><span style="color: red;">*</span> Mohon periksa kembali nominal yang anda masukkan sebelum klik tombol 'ajukan'.</small>
                </td>
                <td align="right"><b>Rp. <span id="jumlahDiterima"></span></b></td>
            </tr>
          <tr>
            <td> </td>
            <td align="right"><button type="submit" class="btn btn-primary" id="saveLoan" onclick="saveLoan()"> <i class="fa fa-send"></i> Ajukan</button></td>
          </tr>
        </table>
      </div>
    </div>
  </div>
</div>
</div>

@endsection
@section('appjs')
<!-- Javascript -->
<script>
$(document).ready(function(){
var start_pkwt         = '{{ $getMember->start_date}}';
var end_pkwt           = '{{ $getMember->end_date}}';
{{--if(start_pkwt == '') {--}}
{{--  PNotify.error({--}}
{{--      title: 'Error.',--}}
{{--      text: 'Data perjanjian PKWT anda belum tersedia / salah tanggal PKWT. Mohon hubungi bagian administrasi untuk info lebih lanjut.',--}}
{{--      type: 'error'--}}
{{--  });--}}
{{--  setTimeout(function(){ window.location.href= '{{route("dashboard")}}'; }, 3000);--}}
{{--}--}}
});


$('#showloan').hide();
$('#saveLoan').attr('disabled', 'disabled');
$(function() {
const rate_of_interest = '{{ $findLoan->rate_of_interest }}' / 100;
const rate_of_bunga_berjalan = '{{ $findLoan->biaya_bunga_berjalan }}' / 100;
const provisi = '{{ $findLoan->provisi }}' / 100;
const biayaAdmin = '{{ $findLoan->biaya_admin }}';
const biayaTransfer = '{{ $findLoan->biaya_transfer }}';
const dayBungaBerjalan = '{{ $dayBungaBerjalan }}';
$('#tenor').on('change', function(){
    // $('#tenor').removeClass('selected');
    // $(this).addClass('selected');

    var calcMonth   = $(this).val();
    if(calcMonth < 1) {
      PNotify.error({
          title: 'Error.',
          text: 'Anda tidak bisa mengajukan pinjaman karna pktw anda segera berakhir. Mohon hubungi bagian administrasi untuk info lebih lanjut.',
          type: 'error'
      });
      $('#pickLoan').slider({disabled: true});
      $('#monthlyLoan').html(0);
    } else{
    if($('#showloan').is(':hidden')) {
      $('#showloan').show('slow');
    } else {
      $('#showloan').hide('slow');
      $('#showloan').show('slow');
    }
    var lockLoan    = parseInt($('#lockLoan').val());
    var rateLoan    = lockLoan * rate_of_interest;
    var provisiLoan = lockLoan * provisi;
    var totalLoan   = lockLoan + rateLoan;

	var monthLoan   = (lockLoan/calcMonth) + rateLoan;

        monthy      = idr(monthLoan);
        totaly      = idr(totalLoan);
    // IDR Currency
    var idrLoan     = idr(lockLoan);
    var rateBungaBerjalan = lockLoan * rate_of_bunga_berjalan;
    var biayaBungaBerjalan = dayBungaBerjalan * rateBungaBerjalan;
    // start calculate base on pick loan nominal
    $('#totalNoRate').html(idrLoan);
    $('#monthlyLoan').html(idr(rateLoan));
    $('#totalyLoan').html(totaly);
    $('#biayaAdmin').html(idr(biayaAdmin));
    $('#biayaTransfer').html(idr(biayaTransfer));
    $('#biayaProvisi').html(idr(provisiLoan));
    $('#jumlahDiterima').html(idr(0));
    $('#bulananLoan').html(idr(monthLoan));
    if(dayBungaBerjalan == 0 && lockLoan == 0){
        $('#biayaBungaBerjalan').html(0);
    }else{
        $('#biayaBungaBerjalan').html(idr(biayaBungaBerjalan));
    }

        // add rule for button submit
    if(lockLoan > 0) {
      $('#saveLoan').removeAttr('disabled');
     } else {
      $('#saveLoan').attr('disabled', 'disabled');
     }
   }
});

$( "#pickLoan" ).slider({
   min: 0,
   step: 50000,
   max: '{{ $findLoan->plafon }}',

   slide: function( event, ui ) {
    var e = document.getElementById("tenor");
    var tenor = e.options[e.selectedIndex].value;
      var numLoan   = ui.value;
      var period    = tenor;
      var rateLoan  = numLoan * rate_of_interest;
      var provisiLoan  = numLoan * provisi;
      var totalLoan = numLoan + rateLoan;
      var monthLoan = parseInt(totalLoan / period);
          monthy    = idr(rateLoan);
          totaly    = idr(totalLoan);
      // IDR Currency
      var idrLoan   = idr(numLoan);
       var rateBungaBerjalan = numLoan * rate_of_bunga_berjalan;
       var biayaBungaBerjalan = dayBungaBerjalan * rateBungaBerjalan;
      // start calculate base on pick loan nominal
      $('#totalNoRate').html(idrLoan);
      $('#monthlyLoan').html(monthy);
      $('#totalyLoan').html(totaly);
      $('#lockLoan').val(numLoan);
      $('#minval').val(idrLoan);
      $('#biayaAdmin').html(idr(biayaAdmin));
     $('#biayaProvisi').html(idr(provisiLoan));
       $('#bulananLoan').html(idr(monthLoan));

       if(dayBungaBerjalan == 0 && numLoan == 0){
           $('#biayaBungaBerjalan').html(0);
       }else{
           $('#biayaBungaBerjalan').html(idr(biayaBungaBerjalan));
       }

       if(numLoan == 0){
          $('#jumlahDiterima').html(idr(0));
      }else{
          $('#jumlahDiterima').html(idr(numLoan - biayaAdmin - provisiLoan - biayaBungaBerjalan - biayaTransfer));
      }

      // add rule for button submit
      if(numLoan > 0) {
        $('#saveLoan').removeAttr('disabled');
      } else {
        $('#saveLoan').attr('disabled', 'disabled');
      }
    }
});
  var nominal = $('#pickLoan').slider('value');
  $('#monthlyLoan').html(nominal);
  $('#lockLoan').val(nominal);
  $('#minval').val(nominal);
});

// processing data by ajax
function saveLoan() {
  const member_id = '{{ \crypt::encrypt(Auth::User()->member->id) }}';
    var e = document.getElementById("tenor");
    var tenor = e.options[e.selectedIndex].value;
  loading('show');
   // process the data
   //  var file_data = $('#images').prop('files')[0];
    var formData = new FormData();
    formData.append('_token', '{{ csrf_token() }}');
    formData.append('member_id', member_id);
    formData.append('loan_id', '{{ \crypt::encrypt($findLoan->id) }}');
    formData.append('value', parseInt($('#lockLoan').val()));
    formData.append('period', tenor);
    // formData.append('images', file_data);

    formData.loan_id = '{{ \crypt::encrypt($findLoan->id) }}';
    formData.value = parseInt($('#lockLoan').val());

        $.ajax({
            type      : 'POST', // define the type of HTTP verb we want to use (POST for our form)
            url       : '{{url("save-loan")}}', // the url where we want to POST
            data      : formData,
            processData: false,  // tell jQuery not to process the data
            contentType: false,
        // using the done promise callback
        success:function(data) {
          if (data.error == 0) {
           PNotify.success({
                  title: 'Success!',
                  text: data.msg,
          });
          loading('hide', 1000);
          setTimeout(function(){ window.location.href= '{{url("member-loans")}}'; }, 3000);
          } else {
          // handling anomali rule
          loading('hide');
          PNotify.error({
              title: 'Gagal.',
              text: data.msg,
              type: 'error'
          });
          }
        },
        // handling error code
        error: function (data) {
          loading('hide');
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
