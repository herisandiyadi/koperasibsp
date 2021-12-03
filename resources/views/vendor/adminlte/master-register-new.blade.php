<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta http-equiv="X-UA-Compatible" content="IE=edge">
	<meta name="viewport" content="width=device-width, initial-scale=1">
	<title>@yield('title_prefix', config('adminlte.title_prefix', ''))
		@yield('title', config('adminlte.title', 'AdminLTE 2'))
		@yield('title_postfix', config('adminlte.title_postfix', ''))</title>

	<!-- CSS -->
	<link rel="stylesheet" href="http://fonts.googleapis.com/css?family=Roboto:400,100,300,500">
	<link rel="stylesheet" href={{ asset("assets/bootstrap/css/bootstrap.min.css")}}>
	<link rel="stylesheet" href={{ asset("assets/font-awesome/css/font-awesome.min.css")}}>
	<link rel="stylesheet" href={{ asset("assets/css/form-elements.css")}}>
	<link rel="stylesheet" href={{ asset("assets/css/style.css")}}>
	<link rel="stylesheet" href="{{ asset('css/custom.css') }}">

	<link href="{{ asset('vendor/kendo-ui/styles/kendo.common.min.css') }}" rel="stylesheet" />
	<link href="{{ asset('vendor/kendo-ui/styles/kendo.bootstrap.min.css') }}" rel="stylesheet" />
	<!-- HTML5 Shim and Respond.js IE8 support of HTML5 elements and media queries -->
	<!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
	<!--[if lt IE 9]>
	<script src="https://oss.maxcdn.com/libs/html5shiv/3.7.0/html5shiv.js"></script>
	<script src="https://oss.maxcdn.com/libs/respond.js/1.4.2/respond.min.js"></script>
	<![endif]-->

	<!-- Favicon and touch icons -->
	<link rel="shortcut icon" href="{{{asset('images/bsp.png')}}}">

	<link rel="apple-touch-icon-precomposed" sizes="144x144" href={{ asset("assets/ico/apple-touch-icon-144-precomposed.png")}}>
	<link rel="apple-touch-icon-precomposed" sizes="114x114" href={{ asset("assets/ico/apple-touch-icon-114-precomposed.png")}}>
	<link rel="apple-touch-icon-precomposed" sizes="72x72" href={{ asset("assets/ico/apple-touch-icon-72-precomposed.png")}}>
	<link rel="apple-touch-icon-precomposed" href={{ asset("assets/ico/apple-touch-icon-57-precomposed.png")}}>

</head>
<body>

@yield('body')

<script>
	var assetUrl = "{{ asset('') }}";
</script>

<!-- Javascript -->
<script src="{{ asset('vendor/kendo-ui/js/jquery.min.js') }}"></script>
<script src="{{ asset('vendor/jquery-ui-1.12.1/jquery-ui.min.js') }}"></script>

<script src="{{ asset('vendor/adminlte/vendor/jquery/dist/jquery.slimscroll.min.js') }}"></script>
<script src="{{ asset('vendor/kendo-ui/js/kendo.all.min.js') }}"></script>

<script src="{{ asset('vendor/adminlte/vendor/bootstrap/dist/js/bootstrap.min.js') }}"></script>

<script src={{ asset("assets/js/jquery.backstretch.min.js")}}></script>
<script src={{ asset("assets/js/retina-1.1.0.min.js")}}></script>
<script src={{ asset("assets/js/scripts.js")}}></script>
<script src="{{ asset('js/custom.js')}}"></script>
<script src="{{ asset('js/global.js')}}"></script>
<script src="{{ asset('js/jquery.mask.min.js')}}"></script>

@if(config('adminlte.plugins.chartjs'))
	<!-- ChartJS -->
	<script src="{{asset('js/Chart.bundle.min.js')}}"></script>
@endif

<script src="{{asset('js/feedback.js')}}"></script>
@yield('adminlte_js')


</body>
</html>
