<!DOCTYPE html>
<html lang="ID">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
        @yield('title', 'Privacy Policy')
        @yield('title_postfix', config('adminlte.title_postfix', ''))</title>
    {{-- icon website --}}
    <link rel="shortcut icon" href="{{{asset('images/bsp.png')}}}">
    <!-- Tell the browser to be responsive to screen width -->
    <meta content="width=device-width, initial-scale=1, maximum-scale=1, user-scalable=no" name="viewport">
    <!-- Bootstrap 3.3.7 -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/bootstrap/dist/css/bootstrap.min.css') }}">
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">
    <link rel="stylesheet" href="{{ asset('css/custom.css') }}">
    {{-- hover css --}}
    <link rel="stylesheet" href="{{ asset('css/hover-min.css') }}">
    {{-- animate css --}}
    <link rel="stylesheet" href="{{ asset('css/animate.min.css') }}">
    {{-- jquery-ui css --}}
    <link href = "{{ asset('vendor/jquery-ui-1.12.1/jquery-ui.min.css') }}" rel = "stylesheet">
    <!-- Font Awesome -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/font-awesome/css/font-awesome.min.css') }}">
    <!-- Ionicons -->
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/vendor/Ionicons/css/ionicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('vendor/adminlte/dist/css/AdminLTE.min.css') }}">

    <!-- pnotify style -->
    {{--<link rel="stylesheet" href="{{ asset('css/pnotify.custom.min.css') }}">--}}

    @if(config('adminlte.plugins.datatables'))
    <!-- DataTables with bootstrap 3 style -->
        <link rel="stylesheet" href="{{asset('css/datatables.min.css')}}">
        <link rel="stylesheet" href="{{asset('css/buttons.dataTables.min.css')}}">

    @endif

    @yield('adminlte_css')
    <script src="{{ asset('vendor/unisharp/laravel-ckeditor/ckeditor.js') }}"></script>

    <script src="{{asset('js/app.js')}}"></script>
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.3/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->

    <!-- Google Font -->
    <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,600,700,300italic,400italic,600italic">
</head>
<body>
<div class="container">
    <section class="content">

     <h2>{{ $privacy->name }}</h2>
        <div class="card" style="margin-bottom: 30px;">
            <div class="card-body">

                <div class="card-text">{!! $privacy->description !!}</div>
            </div>
        </div>

    </section>
</div>
</body>
@include('layouts.partials.footer')
<script src="{{ asset('vendor/kendo-ui/js/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>


{{--CustomJs for dynamic website--}}
<script src="{{ asset('js/custom.js')}}"></script>
{{--Global js for reusable function.--}}
<script src="{{ asset('js/global.js')}}"></script>
{{-- jquery mask --}}
<script src="{{ asset('js/jquery.mask.min.js')}}"></script>
</html>
