<!-- NAVIGATION -->
<nav id="navigation">
	<div class="container">
		<div id="responsive-nav">
			<ul class="main-nav nav navbar-nav">
				<li class="{{ request()->routeIs('client.index') ? 'active' : '' }}">
					<a href="{{ route('client.index') }}">Trang chủ</a>
				</li>
				<li class="{{ request()->routeIs('client.store') ? 'active' : '' }}">
					<a href="{{ route('client.store') }}">Cửa hàng</a>
				</li>
				<li><a href="{{ route('client.index') }}#hot-deals">Khuyến mãi</a></li>
				<li><a href="{{ route('client.index') }}#categories">Danh mục</a></li>
				<li><a href="{{ route('client.index') }}#products">Sản phẩm</a></li>
			</ul>
		</div>
	</div>
</nav>


