<!-- NAVIGATION -->
<nav id="navigation">
	<div class="container">
		<div id="responsive-nav">
			<ul class="main-nav nav navbar-nav">
				<li class="active"><a href="{{ url('/electro') }}">Home</a></li>
				<li><a href="#">Hot Deals</a></li>
				<li><a href="#">Categories</a></li>
				<li><a href="#">Laptops</a></li>
				<li><a href="#">Smartphones</a></li>
				<li><a href="#">Cameras</a></li>
				<li><a href="#">Accessories</a></li>
			</ul>

			<ul class="nav navbar-nav navbar-right">
				@guest
					<li><a href="{{ route('client.login') }}">Đăng nhập</a></li>
					<li><a href="{{ route('client.register') }}">Đăng ký</a></li>
				@else
					<li>
						<a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">Thoát</a>
						<form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
					</li>
				@endguest
			</ul>
		</div>
	</div>
</nav>


