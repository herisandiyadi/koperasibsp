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
</style>

<div class="col-md-10 col-sm-12 col-xs-12">
<div class="box box-info">
  <div class="box-header with-border">
    <h3 class="box-title">Detail Pengajuan Pinjaman :</h3>
  </div>
  <div class="box-body">
    <div class="col-md-12 no-padding">
      <span class="pull-left">Lama cicilan :</span>
      <br>
      <div class="text-center">
        <button value="{{ $finder->period }}" class="selected" disabled>{{ $finder->period }} Bulan</button>
      </div>
      <p>
         <label>Nominal : Rp. </label>
         <input type="text" id="minval" disabled>
      </p>
      <div>
        <span>Rincian Pinjaman :</span>
        <p></p> 
        <table class="table">
          <tr>
            <td>Jenis Pinjaman <br><small>pinjaman yang dipilih</small></td>
            <td>{{ $finder->ms_loans->loan_name }}</td>
          </tr>
          <tr>
            <td>Cicilan Bulanan <br><small>bunga {{ $finder->rate_of_interest }}%</small></td>
            <td>Rp. <span id="monthlyLoan"></span></td>
          </tr>
          <tr>
            <td>Pinjaman Yang diajukan</td>
            <td>Rp. <span id="totalNoRate"></span></td>
          </tr>
          <tr>
            <td>Nominal yang perlu dibayarkan <br><small>ditambah bunga</small></td>
            <td>Rp. <span id="totalyLoan"></span></td>
          </tr>
          <tr>
            <td>Status <br><small>Pengajuan Pinjaman</small></td>
            <td>
              @if ($finder->approval == null)
                 Menunggu persetujuan dari divisi administrasi.
              @else
                 {{ ucwords($finder->approval) }}
              @endif
            </td>
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
    var lockLoan    = parseInt('{{ $finder->value }}');
    var rateLoan    = lockLoan * ( '{{ $finder->rate_of_interest }}' / 100 );
    var totalLoan   = lockLoan + rateLoan;
    var monthLoan   = parseInt(totalLoan / '{{ $finder->period }}');
        monthy      = new Intl.NumberFormat('ID').format(monthLoan);
        totaly      = new Intl.NumberFormat('ID').format(totalLoan);
    // IDR Currency
    var idrLoan     = new Intl.NumberFormat('ID').format(lockLoan);
    // start calculate base on pick loan nominal
    $('#totalNoRate').html(idrLoan);
    $('#monthlyLoan').html(monthy);
    $('#totalyLoan').html(totaly);

</script>
@stop
