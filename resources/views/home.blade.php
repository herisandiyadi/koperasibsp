@extends('adminlte::page')

@section('title', 'AdminLTE')
@section('content')
{{-- style dashboard content --}}
<style>
	.content {
		padding: 0px 15px !important;
	}
	#member {
		background-image: linear-gradient(-20deg, #2b5876 0%, #4e4376 100%) !important;
		color: #fff
	}
	#posisi {
		background-image: radial-gradient(circle 248px at center, #16d9e3 0%, #30c7ec 47%, #46aef7 100%) !important;
		color: #fff
	}
	#transaksi {
		background-image: linear-gradient(to top, #0b6ba3 0%, #3cba92 100%) !important;
		color: #fff;
	}
	hr {
		margin-top:12px;
		margin-bottom: 2px;
	}
	.skin-red-light .main-header .navbar {
    background-color: #fff;
    border-bottom: 1px solid #c8ced3;
	}
	.skin-red-light .main-header .navbar .sidebar-toggle {
	    color: #73818f;
	}
	.skin-red-light .main-header .logo {
	    background-color: #fff;
	    border-bottom: 1px solid #c8ced3;
	    border-right: 1px solid #c8ced3;
	    color: #73818f;
	}
	.skin-red-light .main-header .navbar .sidebar-toggle:hover {
	    background-color: #fff;
	    color:#2f353a;
	}
	.skin-red-light .main-header .logo:hover {
	    background-color: #fff;
	    color:#2f353a;
	}
	.skin-red-light .main-header .navbar .nav>li>a {
	    color: #73818f;
	}
	.skin-red-light .main-header .navbar .nav>li>a:hover {
	    background-color: #fff;
	    color:#2f353a;
	}
	.card {
		padding: 5px;
	}
</style>
<div class="col-md-3 col-sm-6 col-lg-3">
<div class="card" id="member">
  <div class="card-body">
    <h5 class="card-title">Total Member</h5>
    <div class="row">
    	<div class="col-md-3 col-sm-6 col-xs-4">
    		<i class="fa fa-users fa-3x"></i>
    	</div>
    	<div class="col-md-9 col-sm-6 col-xs-8">
    	    <span class="card-title">{{ number_format($allMember) }} <br><small>Member Aktif</small></span>
    	</div>
    </div>
    <hr>
  </div>
</div>
</div>
<div class="col-md-3 col-sm-6 col-lg-3">
<div class="card" id="posisi">
  <div class="card-body">
    <h5 class="card-title">Total Deposit</h5>
    <div class="row">
    	<div class="col-md-3 col-sm-6 col-xs-4">
    		<i class="fa fa-credit-card fa-3x"></i>
    	</div>
    	<div class="col-md-9 col-sm-6 col-xs-8">
    	    <span class="card-title">Rp 56,000,000 <br><small>Rupiah</small></span>
    	</div>
    </div>
    <hr>
  </div>
</div>
</div>
<div class="col-md-3 col-sm-6 col-lg-3">
<div class="card" id="transaksi">
  <div class="card-body">
    <h5 class="card-title">Member Baru</h5>
    <div class="row">
    	<div class="col-md-3 col-sm-6 col-xs-4">
    		<i class="fa fa-signal fa-3x"></i>
    	</div>
    	<div class="col-md-9 col-sm-6 col-xs-8">
    	    <span class="card-title">{{ number_format($newMember) }} <br><small>Bulan Ini</small></span>
    	</div>
    </div>
    <hr>
  </div>
</div>
</div>
<div class="col-md-3 col-sm-6 col-lg-3">
<div class="card" id="member">
  <div class="card-body">
    <h5 class="card-title">Project</h5>
    <div class="row">
    	<div class="col-md-3 col-sm-6 col-xs-4">
    		<i class="fa fa-bar-chart fa-3x"></i>
    	</div>
    	<div class="col-md-9 col-sm-6 col-xs-8">
    	    <span class="card-title">2,000 <br><small>Masih Berjalan</small></span>
    	</div>
    </div>
    <hr>
  </div>
</div>
</div>
@stop