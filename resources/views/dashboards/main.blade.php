@extends('adminlte::page')
@section('title', 'Dashboard')
@section('content')
    <style>
        table {
            margin-top: 20px;
        }
        small.fontSmall {
            font-size: 10px;
        }
        #left {
            margin-top: 20px;
        }
        .info-box.addShadow {
            box-shadow: 0.3px 0.3px;
        }
        a.loaner {
            color: #000;
            text-decoration: none;
        }
        a.loaner:hover {
            color: #000 !important;
            text-decoration: none;
        }
        .col-md-3 a {
            color: #000;
        }
    </style>
    <section class="content-header">
        <h1>
            Dashboard
            <small></small>
        </h1>
        <ol class="breadcrumb">
            <li><a href="#"><i class="fa fa-dashboard"></i> Home</a></li>
            <li class="active">Dashboard</li>
        </ol>
    </section>
    <!-- Main content -->
    <section class="content">
        <!-- Info boxes -->
        @if(!auth()->user()->isMember() || auth()->user()->isSu() || auth()->user()->isPow())
            {{-- rule start --}}
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <a href="{{ asset('members') }}">
                        <div class="info-box hvr-box-shadow-outset">
                            <span class="info-box-icon bg-aqua"><i class="ion ion-ios-people-outline"></i></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Member</span>
                                <span class="info-box-number">{{ number_format($allMember) }} <br><small>Member</small></span>
                            </div>
                    </a>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="#">
                    <div class="info-box hvr-box-shadow-outset">
                        <span class="info-box-icon bg-red"><i class="ion ion-card"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Deposit</span>
                            <span class="info-box-number">Rp {{ number_format($tsDeposit) }} <br><small>Rupiah</small></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="#">
                    <div class="info-box hvr-box-shadow-outset">
                        <span class="info-box-icon bg-green"><i class="ion ion-person-add"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Member Baru</span>
                            <span class="info-box-number">{{ number_format($newMember) }} <br><small>Bulan Ini</small></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ asset('projects') }}">
                    <div class="info-box hvr-box-shadow-outset">
                        <span class="info-box-icon bg-yellow"><i class="fa fa-bar-chart-o"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Proyek</span>
                            <span class="info-box-number">{{ number_format($getProj) }} <br><small>Masih Berjalan</small></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            </div>
            <!-- /.row -->
        @else
            {{-- rule start --}}
            <div class="row">
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <div class="info-box">
                        <span class="info-box-icon bg-aqua"><i class="ion ion-clipboard"></i></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Proyek</span>
                            <span class="info-box-number"> 1 <br><small class="fontSmall">{{ $getProj }}</small></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                    <!-- /.info-box -->
                </div>
                <!-- /.col -->
                <div class="col-md-3 col-sm-6 col-xs-12">
                    <a href="{{ asset('member-deposits') }}">
                        <div class="info-box hvr-box-shadow-outset">
                            <span class="info-box-icon bg-red"><i class="ion ion-ios-paper-outline"></i></span>
                            <div class="info-box-content">
                                <span class="info-box-text">Total Simpanan</span>
                                <span class="info-box-number">{{ number_format($tsDeposit) }} <br><small>Rupiah</small></span>
                            </div>
                            <!-- /.info-box-content -->
                    </a>
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <!-- fix for small devices only -->
            <div class="clearfix visible-sm-block"></div>
            <!-- fix for small devices only -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <a href="{{ url('member-loans') }}">
                    <div class="info-box hvr-box-shadow-outset">
                        <span class="info-box-icon bg-green"><i class="ion ion-card"></i></span>
                        <div class="info-box-content">
                            <span class="info-box-text">Total Pinjaman</span>
                            <span class="info-box-number">{{ number_format($tsLoan) }} <br><small>Rupiah</small></span>
                        </div>
                        <!-- /.info-box-content -->
                    </div>
                </a>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            <div class="col-md-3 col-sm-6 col-xs-12">
                <div class="info-box">
                    <span class="info-box-icon bg-yellow"><i class="fa fa-bar-chart-o"></i></span>
                    <div class="info-box-content">
                        <span class="info-box-text">Plafon</span>
                        <span class="info-box-number">{{ number_format($plafon) }} <br><small>Rupiah</small></span>
                    </div>
                    <!-- /.info-box-content -->
                </div>
                <!-- /.info-box -->
            </div>
            <!-- /.col -->
            </div>
            <!-- /.row -->
    @endif
    {{-- rule end --}}
    <!-- TABLE: info pc -->
        <div class="box box-info">
            <div class="box-header with-border">
                <h3 class="box-title">Selamat Datang</h3>
                <div class="box-tools pull-right">
                    <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                    </button>
                </div>
            </div>
        @if (!auth()->user()->isMember() || auth()->user()->isSu() || auth()->user()->isPow())
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-3 col-sm-6 col-xs-12 text-center" id="left">
                        <img id="img_sex" width="180"
                             src="{{ asset('images/security-guard.png') }}">
                    </div>
                    <div class="col-md-9 col-sm-6 col-xs-12">
                        <table>
                            <tr>
                                <td><b class="text-info">Nama User</b></td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-danger">{{ ucwords(Auth::user()->name) }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td><b class="text-info">Posisi</b></td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-danger">{{ ucwords(Auth::user()->position->description) }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td><b class="text-info">Tanggal</b></td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-danger"><?=date('m / d / Y'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><b class="text-info">Browser</b></td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-danger"><?php echo $_SERVER['HTTP_USER_AGENT'] ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><b class="text-info">IP Komputer</b></td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-danger"><?php echo $_SERVER['REMOTE_ADDR'];; ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><b class="text-info">Nama Komputer</b></td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-danger"><?php echo gethostbyaddr($_SERVER['REMOTE_ADDR']);; ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
                <!-- /.box-body -->
        @else
            <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-3 col-sm-6 col-xs-12 text-center" id="left">
                        @if(Auth::user()->member->picture == null || file_exists('images/'.Auth::user()->member->picture) == false)
                            <img id="img_sex" width="180"
                                 src="{{ asset('images/security-guard.png') }}">
                        @else
                            <img id="img_sex" width="180"
                                 src="{{ asset('images/'.Auth::user()->member->picture) }}">
                        @endif
                    </div>
                    <div class="col-md-6 col-sm-6 col-xs-12">
                        <table>
                            <tr>
                                <td><b class="text-info">Nama User</b></td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-danger">{{ ucwords(Auth::user()->name) }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td><b class="text-info">Posisi</b></td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-danger">{{ ucwords(Auth::user()->position->description) }}</p>
                                </td>
                            </tr>
                            <tr>
                                <td><b class="text-info">Tanggal</b></td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-danger"><?=date('m / d / Y'); ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><b class="text-info">Browser</b></td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-danger"><?php echo $_SERVER['HTTP_USER_AGENT'] ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><b class="text-info">IP Komputer</b></td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-danger"><?php echo $_SERVER['REMOTE_ADDR'];; ?></p>
                                </td>
                            </tr>
                            <tr>
                                <td><b class="text-info">Nama Komputer</b></td>
                            </tr>
                            <tr>
                                <td>
                                    <p class="text-danger"><?php echo gethostbyaddr($_SERVER['REMOTE_ADDR']);; ?></p>
                                </td>
                            </tr>
                        </table>
                    </div>
                    <div id="sideRight">
                        <!-- /.col -->
                        <a href="{{ asset('loan-aggrement') }}" class="loaner">
                            <div class="col-md-3 col-sm-6 col-xs-12">
                                <div class="info-box hvr-box-shadow-outset">
                                    <div class="card">
                                        <div class="text-center" style="padding: 10px">
                                            <img src="{{ asset('images/loan.png') }}" alt="Avatar" style="width:50%">
                                        </div>
                                        <div class="container">
                                            <b>Ajukan Pinjaman</b>
                                            <br>Suku bunga bersaing
                                        </div>
                                        <br>
                                    </div>
                                </div>
                                <!-- /.info-box -->
                            </div>
                        </a>
                    </div>
                </div>
                <!-- /.box-body -->
            @endif
        </div>
        @if (auth()->user()->isSu() || auth()->user()->isPow() || auth()->user()->isAdminArea())
        <div class="row">
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Top 5 Pinjaman</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body with-border">
                        <ol>
                            @foreach($topPinjaman as $top)
                                <li>
                                    <b>{{ $top->ms_loans->loan_name }}</b>
                                    <p>Rp. {{ number_format($top->total) }}</p>
                                    <p>{{ number_format($top->total_user) }} Pinjaman</p>
                                </li>
                            @endforeach

                        </ol>
                    </div>
                    <!-- /.box-body -->
                </div><!-- /.box -->
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Top 5 Peminjam</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body with-border">
                        <ol>
                            @foreach($topPeminjam as $top)
                                <li>
                                    <b>{{ $top->member->first_name }}</b>
                                    <p>Rp. {{ number_format($top->total) }}</p>
                                    <p>{{ number_format($top->total_pinjaman) }} Pinjaman</p>
                                </li>
                            @endforeach

                        </ol>
                    </div>
                </div><!-- /.box -->
            </div>
            <div class="col-md-4 col-sm-6 col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Top 8 Simpanan Anggota</h3>
                        <div class="box-tools pull-right">
                            <button class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i></button>
                        </div><!-- /.box-tools -->
                    </div><!-- /.box-header -->
                    <div class="box-body with-border">
                        <ol>
                            @foreach($topSimpanan as $top)
                                <li>
                                    <b>{{ $top->member->first_name }}</b>
                                    <p>Rp. {{ number_format($top->total) }}</p>
                                </li>
                            @endforeach

                        </ol>
                    </div>
                </div><!-- /.box -->
            </div>
        </div>
        @endif
        <!-- /.box-body -->
    @if (auth()->user()->isSu() || auth()->user()->isPow() || auth()->user()->isAdminArea())
        <!-- grafik member -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Grafik Pendaftaran</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Grafik Anggota Resign</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12">
                        <canvas id="myChartResign"></canvas>
                    </div>
                </div>
            </div>
            <!-- grafik member -->
{{--            <div class="box box-info">--}}
{{--                <div class="box-header with-border">--}}
{{--                    <h3 class="box-title">Anggota aktif</h3>--}}
{{--                    <div class="box-tools pull-right">--}}
{{--                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>--}}
{{--                        </button>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--                <!-- /.box-header -->--}}
{{--                <div class="box-body">--}}
{{--                    <div class="col-md-12">--}}
{{--                        <table id="listActive" class="table table-bordered table-hover table-condensed">--}}
{{--                            <thead>--}}
{{--                            <tr>--}}
{{--                                <th>Nik BSP</th>--}}
{{--                                <th>Name</th>--}}
{{--                                <th>Project</th>--}}
{{--                                <th>Join Date</th>--}}
{{--                                <th>End Date</th>--}}
{{--                                <th class="text-center">Aksi</th>--}}
{{--                            </tr>--}}
{{--                            </thead>--}}
{{--                            <tbody>--}}
{{--                            </tbody>--}}
{{--                        </table>--}}
{{--                    </div>--}}
{{--                </div>--}}
{{--            </div>--}}
            <div class="col-md-6 col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Grafik Simpanan Tahun Lalu</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-12">
                            <canvas id="grafikTahunLalu"></canvas>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
            <div class="col-md-6 col-xs-12">
                <div class="box box-info">
                    <div class="box-header with-border">
                        <h3 class="box-title">Grafik Simpanan Tahun Ini</h3>
                        <div class="box-tools pull-right">
                            <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                            </button>
                        </div>
                    </div>
                    <!-- /.box-header -->
                    <div class="box-body">
                        <div class="col-md-12">
                            <canvas id="grafikTahunIni"></canvas>
                        </div>
                    </div>
                </div>
                <!-- /.box-body -->
            </div>
    @else
        <!-- grafik member -->
            <div class="box box-info">
                <div class="box-header with-border">
                    <h3 class="box-title">Grafik Simpanan Anda</h3>
                    <div class="box-tools pull-right">
                        <button type="button" class="btn btn-box-tool" data-widget="collapse"><i class="fa fa-minus"></i>
                        </button>
                    </div>
                </div>
                <!-- /.box-header -->
                <div class="box-body">
                    <div class="col-md-12">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>
            @endif
            <!-- /.box -->
    </section>
@stop
@section('appjs')
    @if (!auth()->user()->isMember() || auth()->user()->isSu() || auth()->user()->isPow())
        <script>
            $(function() {
                initDatatable('#listActive', 'member-active');
                var month       = {!!$countM!!};
                var arrMonth    = [];
                var arrCounter  = [];
                var maxArrMonth = month.length - 1;
                var j = 0;
                for(i = maxArrMonth; i >= 0; i--){
                    arrMonth[j]   = month[i].month;
                    arrCounter[j] = parseInt(month[i].counter);
                    j++;
                }
                var ctx           = document.getElementById("myChart").getContext('2d');
                var myChart       = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: arrMonth,
                        datasets: [{
                            label: '# Pendaftaran anggota / bulan',
                            data: arrCounter,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                            ],
                            borderColor: [
                                'rgba(255,99,132,1)',
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });

                var chartSimpananTahunLalu = {
                    type: 'line',
                    data: {
                        labels: {!! collect($grafikLastYear['bulan']) !!},
                        datasets: [
                            {
                                label: '# Tahun Lalu',
                                data: {!! collect($grafikLastYear['value']) !!},
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    reverse: false
                                }
                            }]
                        }
                    }
                }

                var cSimpananLalu = document.getElementById('grafikTahunLalu').getContext('2d');
                new Chart(cSimpananLalu, chartSimpananTahunLalu);

                var chartSimpananTahunIni = {
                    type: 'line',
                    data: {
                        labels: {!! collect($grafikThisYear['bulan']) !!},
                        datasets: [
                            {
                                label: '# Tahun Ini',
                                data: {!! collect($grafikThisYear['value']) !!},
                                borderWidth: 1
                            }
                        ]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    reverse: false
                                }
                            }]
                        }
                    }
                }

                var monthResign = {!!$countMResign!!};
                var cSimpananIni = document.getElementById('grafikTahunIni').getContext('2d');
                new Chart(cSimpananIni, chartSimpananTahunIni);
                var arrMonthResign = [];
                var arrCounterResign = [];
                var maxArrMonthResign = monthResign.length - 1;
                var j = 0;
                for(i = maxArrMonthResign; i >= 0; i--){
                    arrMonthResign[j]   = monthResign[i].month;
                    arrCounterResign[j] = parseInt(monthResign[i].counter);
                    j++;
                }

                var chartResignAnggota = document.getElementById("myChartResign").getContext('2d');
                var myChartResign       = new Chart(chartResignAnggota, {
                    type: 'line',
                    data: {
                        labels: arrMonthResign,
                        datasets: [{
                            label: '# Anggota Resign / bulan',
                            data: arrCounterResign,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                            ],
                            borderColor: [
                                'rgba(255,99,132,1)',
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            });
        </script>
    @else
        <script>
            $(function() {
                initDatatable('#listActive', 'member-active');
                var month       = {!!$countDep!!};
                var arrMonth    = [];
                var arrCounter  = [];
                var maxArrMonth = month.length - 1;
                var j = 0;
                for(i = maxArrMonth; i >= 0; i--){
                    arrMonth[j]   = month[i].month;
                    arrCounter[j] = parseInt(month[i].deposit);
                    j++;
                }
                var ctx           = document.getElementById("myChart").getContext('2d');
                var myChart       = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: arrMonth,
                        datasets: [{
                            label: '# Transaksi Simpanan / bulan',
                            data: arrCounter,
                            backgroundColor: [
                                'rgba(255, 99, 132, 0.2)',
                            ],
                            borderColor: [
                                'rgba(255,99,132,1)',
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        scales: {
                            yAxes: [{
                                ticks: {
                                    beginAtZero:true
                                }
                            }]
                        }
                    }
                });
            });
        </script>
    @endif
@stop
