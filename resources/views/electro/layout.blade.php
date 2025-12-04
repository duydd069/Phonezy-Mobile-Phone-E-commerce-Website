<!DOCTYPE html>
<html lang="vi">
	<head>
		<meta charset="utf-8">
		<meta http-equiv="X-UA-Compatible" content="IE=edge">
		<meta name="viewport" content="width=device-width, initial-scale=1">
		
		<meta name="csrf-token" content="{{ csrf_token() }}">
		
		<!-- Primary Meta Tags -->
		<title>@yield('title', 'Electro - Cửa hàng điện tử hàng đầu Việt Nam')</title>
		<meta name="title" content="@yield('meta_title', 'Electro - Cửa hàng điện tử hàng đầu Việt Nam')">
		<meta name="description" content="@yield('meta_description', 'Mua sắm điện tử online với giá tốt nhất. Laptop, smartphone, camera, phụ kiện và nhiều sản phẩm công nghệ khác. Giao hàng nhanh, bảo hành chính hãng.')">
		<meta name="keywords" content="@yield('meta_keywords', 'điện tử, laptop, smartphone, camera, phụ kiện, công nghệ, mua sắm online')">
		<meta name="author" content="Electro">
		<meta name="robots" content="@yield('meta_robots', 'index, follow')">
		<meta name="language" content="Vietnamese">
		<meta name="revisit-after" content="7 days">
		
		<!-- Canonical URL -->
		<link rel="canonical" href="@yield('canonical', url()->current())">
		
		<!-- Open Graph / Facebook -->
		<meta property="og:type" content="@yield('og_type', 'website')">
		<meta property="og:url" content="@yield('og_url', url()->current())">
		<meta property="og:title" content="@yield('og_title', 'Electro - Cửa hàng điện tử hàng đầu Việt Nam')">
		<meta property="og:description" content="@yield('og_description', 'Mua sắm điện tử online với giá tốt nhất. Laptop, smartphone, camera, phụ kiện và nhiều sản phẩm công nghệ khác.')">
		<meta property="og:image" content="@yield('og_image', asset('electro/img/logo.png'))">
		<meta property="og:locale" content="vi_VN">
		<meta property="og:site_name" content="Electro">
		
		<!-- Twitter -->
		<meta name="twitter:card" content="summary_large_image">
		<meta name="twitter:url" content="@yield('twitter_url', url()->current())">
		<meta name="twitter:title" content="@yield('twitter_title', 'Electro - Cửa hàng điện tử hàng đầu Việt Nam')">
		<meta name="twitter:description" content="@yield('twitter_description', 'Mua sắm điện tử online với giá tốt nhất. Laptop, smartphone, camera, phụ kiện và nhiều sản phẩm công nghệ khác.')">
		<meta name="twitter:image" content="@yield('twitter_image', asset('electro/img/logo.png'))">
		
		<!-- Favicon -->
		<link rel="icon" type="image/x-icon" href="{{ asset('electro/img/favicon.ico') }}">
		<link rel="apple-touch-icon" href="{{ asset('electro/img/apple-touch-icon.png') }}">
		
		<!-- Preconnect for performance -->
		<link rel="preconnect" href="https://fonts.googleapis.com">
		<link rel="dns-prefetch" href="https://fonts.googleapis.com">
		
		<!-- Stylesheets -->
		<link href="https://fonts.googleapis.com/css?family=Montserrat:400,500,700" rel="stylesheet">
		<link type="text/css" rel="stylesheet" href="{{ asset('electro/css/bootstrap.min.css') }}"/>
		<link type="text/css" rel="stylesheet" href="{{ asset('electro/css/slick.css') }}"/>
		<link type="text/css" rel="stylesheet" href="{{ asset('electro/css/slick-theme.css') }}"/>
		<link type="text/css" rel="stylesheet" href="{{ asset('electro/css/nouislider.min.css') }}"/>
		<link rel="stylesheet" href="{{ asset('electro/css/font-awesome.min.css') }}">
		<link type="text/css" rel="stylesheet" href="{{ asset('electro/css/style.css') }}"/>
		
		<!-- Structured Data -->
		@stack('structured_data')
		
		@stack('styles')
	</head>
	<body>
		@include('electro.partials.header')
		@include('electro.partials.nav')
		@yield('content')
		@include('electro.partials.footer')
		@include('electro.partials.chatbot-widget')
		<script src="{{ asset('electro/js/jquery.min.js') }}"></script>
		<script src="{{ asset('electro/js/bootstrap.min.js') }}"></script>
		<script src="{{ asset('electro/js/slick.min.js') }}"></script>
		<script src="{{ asset('electro/js/nouislider.min.js') }}"></script>
		<script src="{{ asset('electro/js/jquery.zoom.min.js') }}"></script>
		<script src="{{ asset('electro/js/main.js') }}"></script>
		@stack('scripts')
		<link rel="stylesheet" href="{{ asset('dist/css/category.css') }}">
	</body>
</html>


