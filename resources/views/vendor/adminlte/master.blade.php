<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="csrf-token" content="{{ csrf_token() }}">

    <title>@yield('title_prefix', config('adminlte.title_prefix', ''))
@yield('title', config('adminlte.title', 'AdminLTE 2'))
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
    @if(config('adminlte.plugins.select2'))
        <!-- Select2 -->
        <link rel="stylesheet" href="{{asset('css/select2.css')}}">
    @endif
    <link href="{{ asset('vendor/kendo-ui/styles/kendo.common.min.css') }}" rel="stylesheet" />
    <link href="{{ asset('vendor/kendo-ui/styles/kendo.bootstrap.min.css') }}" rel="stylesheet" />
    <!-- Theme style -->
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
    @if (!is_string($errors) && $errors->count() > 0 or session('error'))
        <script>
            flashed.errors = @json($errors->all())
        </script>
    @endif
    @if (session('success'))
        <script>
            flashed.success = @json(session('success'))
        </script>
    @endif
    @if (session('warning'))
        <script>
            flashed.warning = @json(session('warning'))
        </script>
    @endif
    @if (session('info'))
        <script>
            flashed.info = @json(session('info'))
        </script>
    @endif
    @if (session('failed'))
        <script>
            flashed.errors = @json(session('errors'))
        </script>
@endif
    <!-- base url access in js file by define this script -->
    <script>
         var APP_URL   = {!! json_encode(url('/')) !!};
    </script>
</head>
<body class="hold-transition @yield('body_class')">

@yield('body')
<div id="overlay">
    <div class="col-md-12 text-center">
    <section class="wrapper dark">
      <div class="spinner">
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
        <i></i>
      </div>
      <span>Loading ...</span>
    </section>
    </div>
</div>
</body>
@include('layouts.partials.footer')

{{--<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.min.js') }}"></script>--}}
<script src="{{ asset('vendor/kendo-ui/js/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>

<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>
<script src="{{ asset('vendor/kendo-ui/js/kendo.all.min.js') }}"></script>

@if(config('adminlte.plugins.select2'))
    <!-- Select2 -->
    <script src="{{asset('js/select2.min.js')}}"></script>
@endif

@if(config('adminlte.plugins.datatables'))
    <!-- DataTables with bootstrap 3 renderer -->
	<script src="{{asset('js/datatables.min.js')}}"></script>
	{{-- <script src="{{asset('js/vfs_fonts.min.js')}}"></script> --}}
	<script src="{{asset('js/jszip.min.js')}}"></script>
	<script src="{{asset('js/buttons.html5.min.js')}}"></script>
	<script src="{{asset('js/dataTables.buttons.min.js')}}"></script>
	{{-- <script src="{{asset('js/dataTables.editor.min.js')}}"></script> --}}
	<script src="{{asset('js/buttons.print.min.js')}}"></script>
	<script src="{{asset('js/buttons.colVis.min.js')}}"></script>
	<script src="{{asset('js/pdfmake.min.js')}}"></script>
	<script src="{{asset('js/vfs_fonts.js')}}"></script>








@endif
    {{--CustomJs for dynamic website--}}
    <script src="{{ asset('js/custom.js')}}"></script>
    {{--Global js for reusable function.--}}
    <script src="{{ asset('js/global.js')}}"></script>
    {{-- jquery mask --}}
    <script src="{{ asset('js/jquery.mask.min.js')}}"></script>

@if(config('adminlte.plugins.chartjs'))
    <!-- ChartJS -->
    <script src="{{asset('js/Chart.bundle.min.js')}}"></script>
@endif
<script src="{{asset('js/feedback.js')}}"></script>

@yield('adminlte_js')
@yield('appjs')
@yield('ckeditor')
<script>
    $('form').submit(function (event) {
        if ($(this).hasClass('submitted')) {
            event.preventDefault();
        }
        else {
            $(this).find('button[type=submit]').prop('disabled', true);
            $(this).find(':submit').html('<i class="fa fa-spinner fa-spin"></i> Loading...');
            $(this).addClass('submitted');
        }
    });
</script>
</html>
