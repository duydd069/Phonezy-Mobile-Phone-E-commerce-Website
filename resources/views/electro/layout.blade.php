<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		<title>@yield('title', 'Electro')</title>
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
		<link type="text/css" rel="stylesheet" href="{{ asset('electro/css/bootstrap.min.css') }}"/>
		<link type="text/css" rel="stylesheet" href="{{ asset('electro/css/slick.css') }}"/>
		<link type="text/css" rel="stylesheet" href="{{ asset('electro/css/slick-theme.css') }}"/>
		<link type="text/css" rel="stylesheet" href="{{ asset('electro/css/nouislider.min.css') }}"/>
		<link rel="stylesheet" href="{{ asset('electro/css/font-awesome.min.css') }}">
		<link type="text/css" rel="stylesheet" href="{{ asset('electro/css/style.css') }}"/>
		@stack('styles')
	</head>
	<body>
		@include('electro.partials.header')
		@include('electro.partials.nav')
		@yield('content')
		@include('electro.partials.footer')
		<script src="{{ asset('electro/js/jquery.min.js') }}"></script>
		<script src="{{ asset('electro/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('electro/js/slick.min.js') }}"></script>
		<script src="{{ asset('electro/js/nouislider.min.js') }}"></script>
		<script src="{{ asset('electro/js/jquery.zoom.min.js') }}"></script>
		<script src="{{ asset('electro/js/main.js') }}"></script>
		@stack('scripts')
	</body>
</html>


