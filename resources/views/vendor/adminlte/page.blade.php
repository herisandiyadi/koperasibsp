@extends('adminlte::master')
@section('adminlte_css')
<link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/skins/skin-' . config('adminlte.skin', 'blue') . '.min.css')}} "> @stack('css') @yield('css')
@stop
@section('body_class', 'skin-' . config('adminlte.skin', 'blue') . ' sidebar-mini ' .
(config('adminlte.layout') ? [ 'boxed' => 'layout-boxed', 'fixed' => 'fixed', 'top-nav' => 'layout-top-nav' ][config('adminlte.layout')]
: '') . (config('adminlte.collapse_sidebar') ? ' sidebar-collapse ' : ''))
@section('body')
	<style>
		.funkyradio div {
			clear: both;
			/*margin: 0 50px;*/
			overflow: hidden;
		}
		.funkyradio label {
			/*min-width: 400px;*/
			width: 100%;
			border-radius: 3px;
			border: 1px solid #D1D3D4;
			font-weight: normal;
		}
		.funkyradio input[type="radio"]:empty, .funkyradio input[type="checkbox"]:empty {
			display: none;
		}
		.funkyradio input[type="radio"]:empty ~ label, .funkyradio input[type="checkbox"]:empty ~ label {
			position: relative;
			line-height: 2.5em;
			text-indent: 3.25em;
			cursor: pointer;
			-webkit-user-select: none;
			-moz-user-select: none;
			-ms-user-select: none;
			user-select: none;
		}
		.funkyradio input[type="radio"]:empty ~ label:before, .funkyradio input[type="checkbox"]:empty ~ label:before {
			position: absolute;
			display: block;
			top: 0;
			bottom: 0;
			left: 0;
			content:'';
			width: 2.5em;
			background: #D1D3D4;
			border-radius: 3px 0 0 3px;
		}
		.funkyradio input[type="radio"]:hover:not(:checked) ~ label:before, .funkyradio input[type="checkbox"]:hover:not(:checked) ~ label:before {
			content:'\2714';
			text-indent: .9em;
			color: #C2C2C2;
		}
		.funkyradio input[type="radio"]:hover:not(:checked) ~ label, .funkyradio input[type="checkbox"]:hover:not(:checked) ~ label {
			color: #888;
		}
		.funkyradio input[type="radio"]:checked ~ label:before, .funkyradio input[type="checkbox"]:checked ~ label:before {
			content:'\2714';
			text-indent: .9em;
			color: #333;
			background-color: #ccc;
		}
		.funkyradio input[type="radio"]:checked ~ label, .funkyradio input[type="checkbox"]:checked ~ label {
			color: #777;
		}
		.funkyradio input[type="radio"]:focus ~ label:before, .funkyradio input[type="checkbox"]:focus ~ label:before {
			box-shadow: 0 0 0 3px #999;
		}
		.funkyradio-default input[type="radio"]:checked ~ label:before, .funkyradio-default input[type="checkbox"]:checked ~ label:before {
			color: #333;
			background-color: #ccc;
		}
		.funkyradio-primary input[type="radio"]:checked ~ label:before, .funkyradio-primary input[type="checkbox"]:checked ~ label:before {
			color: #fff;
			background-color: #337ab7;
		}
		.funkyradio-success input[type="radio"]:checked ~ label:before, .funkyradio-success input[type="checkbox"]:checked ~ label:before {
			color: #fff;
			background-color: #5cb85c;
		}
		.funkyradio-danger input[type="radio"]:checked ~ label:before, .funkyradio-danger input[type="checkbox"]:checked ~ label:before {
			color: #fff;
			background-color: #d9534f;
		}
		.funkyradio-warning input[type="radio"]:checked ~ label:before, .funkyradio-warning input[type="checkbox"]:checked ~ label:before {
			color: #fff;
			background-color: #f0ad4e;
		}
		.funkyradio-info input[type="radio"]:checked ~ label:before, .funkyradio-info input[type="checkbox"]:checked ~ label:before {
			color: #fff;
			background-color: #5bc0de;
		}
	</style>
<div class="wrapper">

    <!-- Main Header -->
    <header class="main-header">
        @if(config('adminlte.layout') == 'top-nav')
        <nav class="navbar navbar-static-top">
            <div class="container">
                <div class="navbar-header">
                    <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="navbar-brand">
                            {!! config('adminlte.logo', '<b>Admin</b>LTE') !!}
                        </a>
                    <button type="button" class="navbar-toggle collapsed" data-toggle="collapse" data-target="#navbar-collapse">
                            <i class="fa fa-bars"></i>
                        </button>
                </div>

                <!-- Collect the nav links, forms, and other content for toggling -->
                <div class="collapse navbar-collapse pull-left" id="navbar-collapse">
                    <ul class="nav navbar-nav">
                        @each('adminlte::partials.menu-item-top-nav', $adminlte->menu(), 'item')
                    </ul>
                </div>
                <!-- /.navbar-collapse -->
                @else
                <!-- Logo -->
                <a href="{{ url(config('adminlte.dashboard_url', 'home')) }}" class="logo">
                    <!-- mini logo for sidebar mini 50x50 pixels -->
                    <span class="logo-mini">{!! config('adminlte.logo_mini', '<b>A</b>LT') !!}</span>
                    <!-- logo for regular state and mobile devices -->
                    <span class="logo-lg">{!! config('adminlte.logo', '<b>Admin</b>LTE') !!}</span>
                </a>

                <!-- Header Navbar -->
                <nav class="navbar navbar-static-top" role="navigation">
                    <!-- Sidebar toggle button-->
                    <a href="#" class="sidebar-toggle" data-toggle="push-menu" role="button">
                    <span class="sr-only">{{ trans('adminlte::adminlte.toggle_navigation') }}</span>
                </a> @endif
                    <!-- Navbar Right Menu -->
                    <div class="navbar-custom-menu">

                        <ul class="nav navbar-nav">
                            @include('layouts.components.notification.notif-container')
							<li class="dropdown user user-menu">
								<a href="#" class="dropdown-toggle" data-toggle="dropdown">
								<span>{{auth()->user()->name}}</span>
								</a>
								<ul class="dropdown-menu">
                                @if (!auth()->user()->isMember() || auth()->user()->isSu() || auth()->user()->isPow())
								<!-- User image -->
                                <li class="user-header">
                                    <img class="img-circle" alt="User Image" 
                                        src="{{ asset('images/security-guard.png') }}">
                                    <p>
                                    {{auth()->user()->name}}
                                    <small> Terdaftar sejak. {{\Carbon\Carbon::parse(Auth::user()->created_at)->format('d-M-Y')}}</small>
                                    </p>
                                </li>
                                @else
                                <!-- User image -->
								<li class="user-header">
                                    @if(Auth::user()->member->picture == null || file_exists('images/'.Auth::user()->member->picture) == false)
                                    <img class="img-circle" alt="User Image" 
                                        src="{{ asset('images/security-guard.png') }}">
                                    @else
                                    <img class="img-circle" alt="User Image"
                                        src="{{ asset('images/'.Auth::user()->member->picture) }}">
                                    @endif
									<p>
									{{auth()->user()->name}}
                                    @if(Auth::user()->member->id)
                                    <small> Terdaftar sejak. {{\Carbon\Carbon::parse(Auth::user()->member->join_date)->format('d-M-Y')}}</small>
                                    @else
                                    <small> Terdaftar sejak. {{\Carbon\Carbon::parse(Auth::user()->created_at)->format('d-M-Y')}}</small>
                                    @endif
									</p>
								</li>
                                @endif
								<!-- Menu Footer-->
								<li class="user-footer">
                                    @if (auth()->user()->isMember())
                                    <div class="pull-left">
                                        <a href="{{ url('my-profile') }}" class="btn btn-default btn-flat">Profile</a>
                                    </div>
                                    @endif
									<div class="pull-right">
									<a href="{{ asset('/logout') }}" class="btn btn-default btn-flat">Sign out</a>
									</div>
								</li>
								</ul>
							</li>
							<!-- <li>{{auth()->user()->position->level->name}}</li> -->
                            <li>
                                @if(config('adminlte.logout_method') == 'GET' || !config('adminlte.logout_method') && version_compare(\Illuminate\Foundation\Application::VERSION,
                                '5.3.0', '<'))
                                <a href="{{ url(config('adminlte.logout_url ', 'auth/logout')) }}">
                                    <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte::adminlte.log_out') }}
                                </a>
                            @else
                                <a href="#"
                                   onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                                >
                                    <i class="fa fa-fw fa-power-off"></i> {{ trans('adminlte::adminlte.log_out') }}
                                </a>
                                <form id="logout-form" action="{{ url(config('adminlte.logout_url', 'auth/logout')) }}" method="POST" style="display: none;">
                                    @if(config('adminlte.logout_method'))
                                        {{ method_field(config('adminlte.logout_method')) }}
                                    @endif
                                    {{ csrf_field() }}
                                </form>
                            @endif
                        </li>
                    </ul>
                </div>
                @if(config('adminlte.layout') == 'top-nav')
                </div>
                @endif
            </nav>
        </header>

        @if(config('adminlte.layout') != 'top-nav')
        <!-- Left side column. contains the logo and sidebar -->
        <aside class="main-sidebar">

            <!-- sidebar: style can be found in sidebar.less -->
            <section class="sidebar">

                <!-- Sidebar Menu -->
                <ul class="sidebar-menu" data-widget="tree">
                     @each('adminlte::partials.menu-item ', $adminlte->menu(), 'item')
                </ul>
                <!-- /.sidebar-menu -->
            </section>
            <!-- /.sidebar -->
        </aside>
        @endif

        <!-- Content Wrapper. Contains page content -->
        <div class="content-wrapper">
            @if(config('adminlte.layout') == 'top-nav')
            <div class="container">
            @endif

            <!-- Content Header (Page header) -->
            <section class="content-header">
                @yield('content_header')
            </section>

            <!-- Main content -->
            <section class="content">

                @yield('content')

            </section>
            <!-- /.content -->
            @if(config('adminlte.layout') == 'top-nav')
            </div>
            <!-- /.container -->
            @endif
        </div>
        <!-- /.content-wrapper -->

    </div>
    <!-- ./wrapper -->
@stop

@section('adminlte_js')
    <script src="{{ asset('vendor/adminlte/dist/js/adminlte.min.js') }}"></script>
    @stack('js')
    @yield('js')
@stop
