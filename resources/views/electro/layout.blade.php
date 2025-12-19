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
		
		<!-- Wishlist JavaScript -->
		<script>
		$(document).ready(function() {
			// Xử lý wishlist cho các nút có class .wishlist-btn (trừ trang wishlist/index)
			if (!window.location.pathname.includes('/wishlist')) {
				$(document).on('click', '.wishlist-btn', function(e) {
					e.preventDefault();
					var $btn = $(this);
					var productId = $btn.data('product-id');
					
					if (!productId) {
						console.error('Product ID not found');
						return;
					}
					
					$.ajax({
						url: '{{ route("client.wishlist.toggle") }}',
						method: 'POST',
						data: {
							product_id: productId,
							_token: '{{ csrf_token() }}'
						},
						beforeSend: function() {
							$btn.prop('disabled', true);
						},
						success: function(response) {
							if (response.success) {
								// Cập nhật icon và text
								if (response.in_wishlist) {
									$btn.find('i').removeClass('fa-heart-o').addClass('fa-heart');
									$btn.addClass('active');
									
									// Nếu là nút lớn (btn-wishlist), cập nhật text và style
									if ($btn.hasClass('btn-wishlist')) {
										$btn.css('background', '#27ae60');
										if ($btn.find('span').length) {
											$btn.find('span').text('Đã thêm vào yêu thích');
										}
									} else if ($btn.hasClass('add-to-wishlist')) {
										$btn.css('color', '#e74c3c');
									}
								} else {
									$btn.find('i').removeClass('fa-heart').addClass('fa-heart-o');
									$btn.removeClass('active');
									
									// Nếu là nút lớn (btn-wishlist), cập nhật text và style
									if ($btn.hasClass('btn-wishlist')) {
										$btn.css('background', '#e74c3c');
										if ($btn.find('span').length) {
											$btn.find('span').text('Thêm vào yêu thích');
										}
									} else if ($btn.hasClass('add-to-wishlist')) {
										$btn.css('color', '');
									}
								}
								
								// Hiển thị thông báo
								showWishlistNotification(response.message, 'success');
								
								// Cập nhật số lượng wishlist trong header
								updateWishlistCount();
							}
						},
						error: function(xhr) {
							var message = 'Có lỗi xảy ra. Vui lòng thử lại.';
							if (xhr.status === 401) {
								message = 'Vui lòng đăng nhập để thêm vào wishlist.';
								window.location.href = '{{ route("client.login") }}';
								return;
							}
							if (xhr.responseJSON && xhr.responseJSON.message) {
								message = xhr.responseJSON.message;
							}
							showWishlistNotification(message, 'error');
						},
						complete: function() {
							$btn.prop('disabled', false);
						}
					});
				});
			}
			
			// Kiểm tra và cập nhật trạng thái wishlist khi trang load
			@auth
			$('.wishlist-btn').each(function() {
				var $btn = $(this);
				var productId = $btn.data('product-id');
				if (productId) {
					// Kiểm tra xem sản phẩm có trong wishlist không
					$.ajax({
						url: '{{ route("client.wishlist.index") }}',
						method: 'GET',
						success: function(response) {
							// Parse HTML response để tìm product ID
							var $response = $(response);
							var isInWishlist = $response.find('[data-product-id="' + productId + '"]').length > 0;
							if (isInWishlist) {
								$btn.find('i').removeClass('fa-heart-o').addClass('fa-heart');
								$btn.addClass('active');
							}
						}
					});
				}
			});
			@endauth
		});
		
		function updateWishlistCount() {
			// Reload để cập nhật số lượng wishlist trong header
			// Hoặc có thể dùng AJAX để cập nhật mà không reload
			setTimeout(function() {
				location.reload();
			}, 500);
		}
		
		function showWishlistNotification(message, type) {
			var bgColor = type === 'success' ? '#5cb85c' : '#d9534f';
			var $notification = $('<div>')
				.css({
					'position': 'fixed',
					'top': '20px',
					'right': '20px',
					'background': bgColor,
					'color': '#fff',
					'padding': '15px 20px',
					'border-radius': '4px',
					'z-index': '9999',
					'box-shadow': '0 2px 10px rgba(0,0,0,0.2)',
					'max-width': '300px'
				})
				.text(message)
				.appendTo('body');
			
			setTimeout(function() {
				$notification.fadeOut(300, function() {
					$(this).remove();
				});
			}, 3000);
		}
		</script>
		
		@stack('scripts')
		<link rel="stylesheet" href="{{ asset('dist/css/category.css') }}">
	</body>
</html>


