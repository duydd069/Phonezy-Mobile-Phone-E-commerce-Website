<!-- HEADER -->
<header>
    <!-- TOP HEADER -->
    <div id="top-header">
        <div class="container">
            <ul class="header-links pull-left">
                <li><a href="#"><i class="fa fa-phone"></i> +021-95-51-84</a></li>
                <li><a href="#"><i class="fa fa-envelope-o"></i> email@email.com</a></li>
                <li><a href="#"><i class="fa fa-map-marker"></i> 1734 Stonecoal Road</a></li>
            </ul>
            <ul class="header-links pull-right">
                <li><a href="#"><i class="fa fa-dollar"></i> USD</a></li>
                @auth
                    <li class="dropdown">
                        <a href="#" class="dropdown-toggle" data-toggle="dropdown" aria-expanded="false">
                            <i class="fa fa-user-o"></i> 
                            <span class="account-name">{{ auth()->user()->name ?? 'My Account' }}</span>
                            <i class="fa fa-caret-down"></i>
                        </a>
                        <ul class="dropdown-menu" role="menu">
                            <li>
                                <a href="{{ route('client.account.index') }}">
                                    <i class="fa fa-user"></i> Thông tin tài khoản
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('client.orders.index') }}">
                                    <i class="fa fa-shopping-bag"></i> Đơn hàng của tôi
                                </a>
                            </li>
                            <li>
                                <a href="{{ route('client.coupons.index') }}">
                                    <i class="fa fa-tag"></i> Mã khuyến mãi
                                </a>
                            </li>
                            <li class="divider"></li>
                            <li>
                                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                                    <i class="fa fa-sign-out"></i> Đăng xuất
                                </a>
                                <form id="logout-form-header" action="{{ route('logout') }}" method="POST" style="display:none;">
                                    @csrf
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li>
                        <a href="{{ route('client.login') }}">
                            <i class="fa fa-sign-in"></i> Login
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
    <!-- /TOP HEADER -->

    <!-- MAIN HEADER -->
    <div id="header">
        <div class="container">
            <div class="row">
                <!-- LOGO -->
                <div class="col-md-3">
                    <div class="header-logo">
                        <a href="{{ url('/electro') }}" class="logo">
                            <img src="{{ asset('electro/img/logo.png') }}" alt="">
                        </a>
                    </div>
                </div>
                <!-- /LOGO -->

                <!-- SEARCH BAR -->
                <div class="col-md-6">
                    <div class="header-search">
                        <form>
                            <select class="input-select">
                                <option value="0">All Categories</option>
                                <option value="1">Category 01</option>
                                <option value="1">Category 02</option>
                            </select>
                            <input class="input" placeholder="Search here">
                            <button class="search-btn">Search</button>
                        </form>
                    </div>
                </div>
                <!-- /SEARCH BAR -->

                <!-- ACCOUNT -->
                <div class="col-md-3 clearfix">
                    <div class="header-ctn">
                        <!-- Wishlist -->
                        @auth
                        <div>
                            <a href="{{ route('client.wishlist.index') }}">
                                <i class="fa fa-heart-o"></i>
                                <span>Your Wishlist</span>
                                <div class="qty">{{ $wishlistCount ?? 0 }}</div>
                            </a>
                        </div>
                        @else
                        <div>
                            <a href="{{ route('client.login') }}" title="Đăng nhập để xem wishlist">
                                <i class="fa fa-heart-o"></i>
                                <span>Your Wishlist</span>
                                <div class="qty">0</div>
                            </a>
                        </div>
                        @endauth
                        <!-- /Wishlist -->

                        <!-- Cart -->
                      <div>
                        <a href="{{ route('cart.index') }}">
                            <i class="fa fa-shopping-cart"></i>
                            <span>Your Cart</span>
                            <div class="qty">{{ $cartCount ?? 0 }}</div>
                        </a>
                    </div>
                        <!-- /Cart -->

                        <!-- Menu Toogle -->
                        <div class="menu-toggle">
                            <a href="#">
                                <i class="fa fa-bars"></i>
                                <span>Menu</span>
                            </a>
                        </div>
                        <!-- /Menu Toogle -->
                    </div>
                </div>
                <!-- /ACCOUNT -->
            </div>
        </div>
    </div>
    <!-- /MAIN HEADER -->
</header>
<!-- /HEADER -->

<style>
/* Dropdown menu cho My Account */
.header-links .dropdown {
    position: relative;
}

.header-links .dropdown-toggle {
    cursor: pointer;
}

.header-links .dropdown-menu {
    position: absolute;
    top: 100%;
    right: 0;
    z-index: 1000;
    min-width: 200px;
    padding: 5px 0;
    margin: 2px 0 0;
    background-color: #fff;
    border: 1px solid #ccc;
    border-radius: 4px;
    box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    display: none;
}

.header-links .dropdown:hover .dropdown-menu,
.header-links .dropdown.open .dropdown-menu {
    display: block;
}

.header-links .dropdown-menu li {
    list-style: none;
    margin: 0;
}

.header-links .dropdown-menu li a {
    display: block;
    padding: 10px 15px;
    color: #333;
    text-decoration: none;
    white-space: nowrap;
}

.header-links .dropdown-menu li a:hover {
    background-color: #f5f5f5;
    color: #007bff;
}

.header-links .dropdown-menu li a i {
    margin-right: 8px;
    width: 20px;
}

.header-links .dropdown-menu .divider {
    height: 1px;
    margin: 5px 0;
    overflow: hidden;
    background-color: #e5e5e5;
}

.header-links .dropdown-toggle .fa-caret-down {
    margin-left: 5px;
    font-size: 12px;
}

.header-links .account-name {
    max-width: 120px;
    display: inline-block;
    overflow: hidden;
    text-overflow: ellipsis;
    white-space: nowrap;
    vertical-align: middle;
}

@media (max-width: 768px) {
    .header-links .account-name {
        max-width: 80px;
    }
}
</style>

<script>
// Đảm bảo dropdown hoạt động trên mobile
document.addEventListener('DOMContentLoaded', function() {
    const dropdowns = document.querySelectorAll('.header-links .dropdown');
    dropdowns.forEach(function(dropdown) {
        const toggle = dropdown.querySelector('.dropdown-toggle');
        if (toggle) {
            toggle.addEventListener('click', function(e) {
                e.preventDefault();
                dropdown.classList.toggle('open');
            });
        }
    });
    
    // Đóng dropdown khi click bên ngoài
    document.addEventListener('click', function(e) {
        if (!e.target.closest('.dropdown')) {
            dropdowns.forEach(function(dropdown) {
                dropdown.classList.remove('open');
            });
        }
    });
});
</script>
