<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<title>FormWizard_v1</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<meta name="author" content="colorlib.com">

	<!-- MATERIAL DESIGN ICONIC FONT -->
	<!-- MATERIAL DESIGN ICONIC FONT -->
	<link rel="stylesheet" href={{ asset('fonts/material-design-iconic-font/css/material-design-iconic-font.css')}}>

	<!-- STYLE CSS -->
	<link rel="stylesheet" href={{ asset('css/style.css') }}>
</head>
<body>

@yield('body')
<!-- JQUERY -->
<script src={{ asset('js/jquery-3.3.1.min.js')}}></script>

<!-- JQUERY STEP -->
<script src={{ asset('js/jquery.steps.js')}}></script>
<script src={{ asset('js/main.js')}}></script>
<!-- Template created and distributed by Colorlib -->
</body>
</html>
