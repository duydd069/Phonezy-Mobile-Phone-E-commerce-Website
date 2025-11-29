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

				<!-- DROPDOWN DANH MỤC -->
				<li class="dropdown side-dropdown">
					<a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
						Danh mục <i class="fa fa-angle-right"></i>
					</a>
					<div class="custom-menu">
						<ul class="list-links">
							@foreach ($categories as $cat)
								<li><a href="{{ route('client.category.show', $cat->slug) }}">{{ $cat->name }}</a></li>
							@endforeach
						</ul>
					</div>
				</li>


				<li><a href="{{ route('client.index') }}#hot-deals">Khuyến mãi</a></li>
				<li><a href="{{ route('client.index') }}#products">Sản phẩm</a></li>
			</ul>


			<ul class="nav navbar-nav navbar-right">
				@guest
					<li><a href="{{ route('client.login') }}">Login</a></li>
					<li><a href="{{ route('client.register') }}">Register</a></li>
				@else
					<li>
						<a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Logout</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
					</li>
				@endguest
			</ul>
		</div>
	</div>
</nav>


