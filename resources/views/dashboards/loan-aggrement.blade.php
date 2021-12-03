@extends('adminlte::page')
@section('title', 'List Pinjaman')
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
</style>

<section class="content-header">
    <h1>Daftar pinjaman yang tersedia :</h1>
</section>
{{-- <a href="" class="hvr-bounce-in">ini</a> --}}
<!-- /.col -->
@foreach ($getLoans as $el)
{{-- <a href="{{ asset('loan-aggrement/'.\Crypt::encrypt($el->id)) }}" class="loaner">
<div class="col-md-4 col-sm-6 col-xs-12">
  <div class="info-box hvr-box-shadow-outset">
    <span class="info-box-icon bg-green"><i class="fa fa-handshake-o"></i></span>

    <div class="info-box-content">
      <span class="info-box-text">{{ $el->loan_name}}</span>
      <span class="info-box-number">Rp {{ number_format($el->plafon) }} <br><small>Maksimal Pinjaman</small></span>
    </div>
    <!-- /.info-box-content -->
  </div>
  <!-- /.info-box -->
</div>
</a> --}}
<a href="{{ asset('loan-aggrement/'.\Crypt::encrypt($el->id)) }}" class="loaner">
<div class="col-md-3 col-sm-6 col-xs-6">
  <div class="info-box hvr-box-shadow-outset">
  <div class="card">
  <div class="text-center">
      @if($el->logo !== null)
          <img src="{{ URL::to('/') }}/images/{{ $el->logo }}" width="50%" style="margin-top:10px; margin-bottom: 10px;">
      @else
        <img src="{{ asset('images/mn.png') }}" alt="Avatar" style="width:50%">
      @endif
  </div>
  <div class="container">
    <b>{{ $el->loan_name}}</b>
    <br>Rp {{ number_format($el->plafon) }} <br><small>Maksimal Pinjaman</small></span>
  </div>
  <br>
</div>
</div>
</a>
</div>
@endforeach
<!-- /.col -->
@php
@endphp
@endsection
