<!-- NAVIGATION -->
<nav id="navigation">
	<div class="container">
		<div id="responsive-nav">
			<ul class="main-nav nav navbar-nav">
				<li class="{{ request()->routeIs('client.index') ? 'active' : '' }}">
					<a href="{{ route('client.index') }}">Trang chủ</a>
				</li>
				<li class="{{ request()->routeIs('client.promotions') ? 'active' : '' }}">
					<a href="{{ route('client.promotions') }}">Khuyến mãi</a>
				</li>
				<li class="category-dropdown">
					<a href="{{ route('client.index') }}#categories">
						Danh mục
						<i class="fa fa-angle-right"></i>
					</a>
					<div class="category-mega-menu">
						<div class="category-menu-container">
							<ul class="category-menu-primary">
								@php
									$allCategories = $categories ?? \App\Models\Category::all();
									// Định nghĩa các nhóm danh mục chính và từ khóa tương ứng
									$categoryGroups = [
										'Điện thoại' => ['điện thoại', 'smartphone', 'phone', 'iphone', 'samsung'],
										'Máy tính bảng' => ['máy tính bảng', 'tablet', 'ipad'],
										'Phụ kiện' => ['phụ kiện', 'accessories', 'accessory', 'ốp', 'dán', 'cáp', 'sạc'],
										'Âm thanh' => ['âm thanh', 'audio', 'loa', 'tai nghe', 'headphone', 'speaker', 'earphone'],
										'Đồng hồ' => ['đồng hồ', 'watch', 'smartwatch']
									];
								@endphp
								@foreach($categoryGroups as $groupName => $keywords)
									@php
										// Tìm các category khớp với keywords
										$matchedCategories = $allCategories->filter(function($category) use ($keywords, $groupName) {
											$categoryNameLower = mb_strtolower($category->name, 'UTF-8');
											foreach($keywords as $keyword) {
												if(mb_stripos($categoryNameLower, $keyword) !== false) {
													return true;
												}
											}
											// Kiểm tra tên group
											if(mb_stripos($categoryNameLower, mb_strtolower($groupName, 'UTF-8')) !== false) {
												return true;
											}
											return false;
										});
									@endphp
									@if($matchedCategories->count() > 0)
										<li class="category-item" data-category-group="{{ strtolower(str_replace(' ', '-', $groupName)) }}">
											<a href="#">
												{{ $groupName }}
												<i class="fa fa-angle-right"></i>
											</a>
											<div class="category-submenu">
												<ul class="category-submenu-list">
													@foreach($matchedCategories as $category)
														<li>
															<a href="{{ route('client.category.show', $category->slug) }}">
																{{ $category->name }}
															</a>
														</li>
													@endforeach
												</ul>
											</div>
										</li>
									@else
										{{-- Nếu không có category nào khớp, vẫn hiển thị group với link đến store --}}
										<li class="category-item">
											<a href="{{ route('client.store') }}">
												{{ $groupName }}
											</a>
										</li>
									@endif
								@endforeach
								{{-- Hiển thị các category còn lại không thuộc group nào --}}
								@php
									$usedCategoryIds = collect($categoryGroups)->map(function($keywords, $groupName) use ($allCategories) {
										return $allCategories->filter(function($category) use ($keywords, $groupName) {
											$categoryNameLower = mb_strtolower($category->name, 'UTF-8');
											foreach($keywords as $keyword) {
												if(mb_stripos($categoryNameLower, $keyword) !== false) {
													return true;
												}
											}
											if(mb_stripos($categoryNameLower, mb_strtolower($groupName, 'UTF-8')) !== false) {
												return true;
											}
											return false;
										})->pluck('id');
									})->flatten()->unique();
									
									$remainingCategories = $allCategories->whereNotIn('id', $usedCategoryIds);
								@endphp
								@if($remainingCategories->count() > 0)
									@foreach($remainingCategories->take(5) as $category)
										<li class="category-item">
											<a href="{{ route('client.category.show', $category->slug) }}">
												{{ $category->name }}
											</a>
										</li>
									@endforeach
								@endif
							</ul>
						</div>
					</div>
				</li>
			</ul>

			<!-- <ul class="nav navbar-nav navbar-right">
				@guest
					<li><a href="{{ route('client.login') }}">Login</a></li>
					<li><a href="{{ route('client.register') }}">Register</a></li>
				@endguest
			</ul> -->
		</div>
	</div>
</nav>

<style>
/* Navigation Background - Trắng + Xám + Đỏ */
#navigation {
	background: #F5F5F5 !important;
	color: #333 !important;
	border-top: 2px solid #E4E7ED !important;
	border-bottom: 2px solid #E4E7ED !important;
}

#navigation * {
	color: #333 !important;
}

#navigation a {
	color: #333 !important;
}

#navigation .main-nav > li > a {
	color: #333 !important;
}

#navigation .main-nav > li.active > a {
	color: #D10024 !important;
}

#navigation .main-nav > li > a:hover {
	color: #D10024 !important;
	background-color: rgba(209, 0, 36, 0.05) !important;
}

/* Category Dropdown Styles */
.category-dropdown {
	position: relative;
}

.category-dropdown > a {
	position: relative;
}

.category-dropdown > a i.fa-angle-right {
	margin-left: 5px;
	font-size: 12px;
	transition: transform 0.3s ease;
}

.category-dropdown > a {
	color: #333 !important;
}

.category-dropdown:hover > a {
	color: #D10024 !important;
}

.category-dropdown:hover > a i.fa-angle-right {
	transform: rotate(90deg);
}

.category-mega-menu {
	position: absolute;
	top: 100%;
	left: 0;
	background: #fff;
	border-radius: 4px;
	box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
	min-width: 250px;
	padding: 15px 0;
	margin-top: 5px;
	opacity: 0;
	visibility: hidden;
	transform: translateY(-10px);
	transition: all 0.3s ease;
	z-index: 1000;
	display: flex;
}

.category-dropdown:hover .category-mega-menu {
	opacity: 1;
	visibility: visible;
	transform: translateY(0);
}

.category-menu-container {
	width: 100%;
	display: flex;
}

.category-menu-primary {
	list-style: none;
	margin: 0;
	padding: 0;
	width: 100%;
}

.category-menu-primary .category-item {
	position: relative;
	margin: 0;
	padding: 0;
}

.category-menu-primary .category-item > a {
	display: block;
	padding: 10px 20px;
	color: #2B2D42;
	text-decoration: none;
	transition: all 0.3s ease;
	font-size: 14px;
	position: relative;
}

.category-menu-primary .category-item > a:hover {
	background-color: #f8f9fa;
	color: #D10024;
}

.category-menu-primary .category-item > a i.fa-angle-right {
	float: right;
	margin-top: 3px;
	font-size: 12px;
	transition: transform 0.3s ease;
}

.category-menu-primary .category-item:hover > a i.fa-angle-right {
	transform: translateX(3px);
}

/* Submenu Styles */
.category-submenu {
	position: absolute;
	left: 100%;
	top: 0;
	background: #fff;
	border-radius: 4px;
	box-shadow: 0 2px 10px rgba(0, 0, 0, 0.15);
	min-width: 200px;
	padding: 10px 0;
	margin-left: 5px;
	opacity: 0;
	visibility: hidden;
	transform: translateX(-10px);
	transition: all 0.3s ease;
	z-index: 1001;
}

.category-item:hover .category-submenu {
	opacity: 1;
	visibility: visible;
	transform: translateX(0);
}

.category-submenu-list {
	list-style: none;
	margin: 0;
	padding: 0;
}

.category-submenu-list li {
	margin: 0;
	padding: 0;
}

.category-submenu-list li a {
	display: block;
	padding: 8px 20px;
	color: #2B2D42;
	text-decoration: none;
	font-size: 13px;
	transition: all 0.3s ease;
}

.category-submenu-list li a:hover {
	background-color: #f8f9fa;
	color: #D10024;
	padding-left: 25px;
}

/* Active state for category dropdown */
.category-dropdown.active > a,
.main-nav .category-dropdown.active > a {
	color: #D10024;
	border-bottom: 2px solid #D10024;
}

/* Responsive styles */
@media (max-width: 991px) {
	.category-mega-menu {
		position: static;
		opacity: 1;
		visibility: visible;
		transform: none;
		box-shadow: none;
		border: none;
		padding: 0;
		margin: 0;
		display: none;
	}
	
	.category-dropdown:hover .category-mega-menu,
	.category-dropdown.active .category-mega-menu {
		display: block;
	}
	
	.category-submenu {
		position: static;
		opacity: 1;
		visibility: visible;
		transform: none;
		box-shadow: none;
		margin: 0;
		padding: 0 0 0 20px;
		display: none;
	}
	
	.category-item:hover .category-submenu,
	.category-item.active .category-submenu {
		display: block;
	}
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
	const categoryDropdown = document.querySelector('.category-dropdown');
	const categoryItems = document.querySelectorAll('.category-item');
	
	// Handle mobile click
	if (window.innerWidth <= 991) {
		if (categoryDropdown) {
			const categoryLink = categoryDropdown.querySelector('a');
			if (categoryLink) {
				categoryLink.addEventListener('click', function(e) {
					e.preventDefault();
					categoryDropdown.classList.toggle('active');
				});
			}
		}
		
		categoryItems.forEach(function(item) {
			const itemLink = item.querySelector('a');
			if (itemLink) {
				itemLink.addEventListener('click', function(e) {
					const submenu = item.querySelector('.category-submenu');
					if (submenu) {
						e.preventDefault();
						item.classList.toggle('active');
					}
				});
			}
		});
	}
	
	// Close dropdown when clicking outside
	document.addEventListener('click', function(e) {
		if (!e.target.closest('.category-dropdown')) {
			if (categoryDropdown) {
				categoryDropdown.classList.remove('active');
			}
			categoryItems.forEach(function(item) {
				item.classList.remove('active');
			});
		}
	});
});
</script>

