<!-- HEADER -->
<header>
    <div id="header">
        <div class="container">
            <div class="row" style="display: flex; align-items: center;">
                <div class="col-md-2">
                    <div class="header-logo">
                        <a href="{{ route('client.index') }}" class="logo">
                            @if(file_exists(public_path('storage/logos/logo.png')) || file_exists(public_path('storage/logos/logo.jpg')))
                                <img src="{{ url('storage/logos/logo.' . (file_exists(public_path('storage/logos/logo.png')) ? 'png' : 'jpg')) }}" alt="PhoneZy Logo" class="logo-img" style="max-height: 1000px;">
                            @else
                                <div class="logo-text">
                                    <span class="logo-phone" style="font-weight: bold; color: #D10024; font-size: 24px;">Phone</span><span class="logo-zy" style="font-weight: bold; color: #1E1F29; font-size: 24px;">Zy</span>
                                </div>
                            @endif
                        </a>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="header-search">
                        <form action="#" method="GET" style="display: flex; width: 100%;">
                            <input id="search-input" class="input" placeholder="Search here" style="width: 100%; border-radius: 40px 0px 0px 40px; padding: 0px 20px;">
                            <div id="search-suggestions" class="search-suggestions"></div>
                            <button class="search-btn" type="submit" style="border-radius: 0px 40px 40px 0px; background: #D10024; color: #fff; font-weight: bold; padding: 0 20px; border: none; height: 40px; display:flex; align-items:center; gap:8px;">
                                <i class="fa fa-search search-icon" aria-hidden="true" style="display:none;"></i>
                                <span class="search-text">Search</span>
                            </button>
                        </form>
                    </div>
                </div>

                <div class="col-md-5">
                    <div class="header-ctn" style="display: flex; justify-content: flex-end; align-items: center; gap: 25px;">
                        
                        <div class="wishlist">
                            <a href="{{ Auth::check() ? route('client.wishlist.index') : route('client.login') }}" style="text-align: center; display: block; position: relative;">
                                <i class="fa fa-heart-o" style="font-size: 18px;"></i>
                                <span style="display: block; font-size: 10px; text-transform: uppercase;">Wishlist</span>
                                <div class="qty" style="position: absolute; right: -10px; top: -10px; width: 18px; height: 18px; line-height: 18px; text-align: center; border-radius: 50%; font-size: 10px; color: #FFF; background-color: #D10024;">{{ $wishlistCount ?? 0 }}</div>
                            </a>
                        </div>

                        <div class="cart">
                            <a href="{{ route('cart.index') }}" style="text-align: center; display: block; position: relative;">
                                <i class="fa fa-shopping-cart" style="font-size: 18px;"></i>
                                <span style="display: block; font-size: 10px; text-transform: uppercase;">Your Cart</span>
                                <div class="qty" style="position: absolute; right: -10px; top: -10px; width: 18px; height: 18px; line-height: 18px; text-align: center; border-radius: 50%; font-size: 10px; color: #FFF; background-color: #D10024;">{{ $cartCount ?? 0 }}</div>
                            </a>
                        </div>

                        <div class="dropdown">
                            @auth
                                <a class="dropdown-toggle" data-toggle="dropdown" aria-expanded="true" style="cursor: pointer; text-align: center; display: block;">
                                    <i class="fa fa-user-o" style="font-size: 18px;"></i>
                                    <span style="display: block; font-size: 10px; text-transform: uppercase;">{{ auth()->user()->name }}</span>
                                </a>
                                <ul class="dropdown-menu dropdown-menu-right" style="width: 200px;">
                                    <li><a href="{{ route('client.account.index') }}"><i class="fa fa-user"></i> Tài khoản</a></li>
                                    <li><a href="{{ route('client.orders.index') }}"><i class="fa fa-shopping-bag"></i> Đơn hàng</a></li>
                                    <li class="divider"></li>
                                    <li>
                                        <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form-header').submit();">
                                            <i class="fa fa-sign-out"></i> Đăng xuất
                                        </a>
                                        <form id="logout-form-header" action="{{ route('logout') }}" method="POST" style="display:none;">@csrf</form>
                                    </li>
                                </ul>
                            @else
                                <a href="{{ route('client.login') }}" style="text-align: center; display: block;">
                                    <i class="fa fa-user-o" style="font-size: 18px;"></i>
                                    <span style="display: block; font-size: 10px; text-transform: uppercase;">Login</span>
                                </a>
                            @endauth
                        </div>

                        <div class="menu-toggle" style="display: none;">
                            <a href="#"><i class="fa fa-bars"></i></a>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</header>
<!-- /HEADER -->


<style>
/* Logo Styles */
.header-logo .logo {
    display: flex;
    align-items: center;
    text-decoration: none;
    transition: transform 0.3s ease;
}

.header-logo .logo:hover {
    transform: scale(1.05);
}

.header-logo .logo-img {
    max-height: 90px;
    width: auto;
    object-fit: contain;
    transition: all 0.3s ease;
}

.header-logo .logo-text {
    display: flex;
    align-items: center;
    font-size: 28px;
    font-weight: 700;
    letter-spacing: -1px;
}

.header-logo .logo-phone {
    color: #333;
    font-weight: 700;
}

.header-logo .logo-zy {
    color: #D10024; /* Đỏ làm màu chính */
    font-weight: 700;
    border-bottom: 2px solid #FFD700; /* Vàng làm điểm nhấn nhỏ */
}

/* Header Improvements - Trắng + Xám + Đỏ */
#top-header {
    background: #FFFFFF;
    border-bottom: 2px solid #E4E7ED;
    color: #333;
}

#top-header * {
    color: #333 !important;
}

#top-header a {
    color: #333 !important;
}

#top-header .header-links a {
    color: #333 !important;
}

#top-header .header-links a:hover {
    color: #D10024 !important;
    background-color: rgba(209, 0, 36, 0.05);
}

#header {
    background: #FFFFFF;
    border-bottom: 1px solid #E4E7ED;
    box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
    position: sticky;
    top: 0;
    z-index: 999;
    color: #333;
}

#header * {
    color: #333 !important;
}

#header a {
    color: #333 !important;
}

#header .header-ctn a {
    color: #333 !important;
}

#header .header-ctn a:hover {
    color: #D10024 !important;
}

.header-search {
    position: relative;
}

.header-search form {
    display: flex;
    gap: 0;
}

.header-search .input-select {
    flex: 1;
    padding: 12px 20px;
    border: 2px solid #E4E7ED;
    border-radius: 30px 0 0 30px;
    font-size: 14px;
    transition: all 0.3s ease;
}

.header-search .input-select:focus {
    outline: none;
    border-color: #D10024;
    box-shadow: 0 0 0 3px rgba(209, 0, 36, 0.1);
}

.header-search .search-btn {
    padding: 12px 30px;
    background: linear-gradient(135deg, #D10024 0%, #B71C1C 100%);
    border: none;
    border-radius: 0 30px 30px 0;
    color: #fff;
    font-weight: 600;
    cursor: pointer;
    transition: all 0.3s ease;
}

.header-search .search-btn:hover {
    background: linear-gradient(135deg, #B71C1C 0%, #D10024 100%);
    transform: translateY(-2px);
    box-shadow: 0 4px 12px rgba(255, 255, 255, 0.3);
}

.header-ctn > div {
    transition: transform 0.3s ease;
}

.header-ctn > div:hover {
    transform: translateY(-3px);
}

.header-ctn a {
    transition: all 0.3s ease;
}

.header-ctn a:hover {
    color: #D10024;
}

.header-ctn .qty {
    background: linear-gradient(135deg, #D10024 0%, #B71C1C 100%);
    box-shadow: 0 2px 8px rgba(209, 0, 36, 0.3);
}

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

/* Banner Carousel Styles */
#banner-carousel {
    width: 65%;
    margin: 0;
    position: relative;
    margin-left: 18%;
}

#banner-carousel .carousel-inner {
    width: 100%;
    max-height: 500px;
    overflow: hidden;
}

#banner-carousel .carousel-inner .item {
    width: 100%;
    height: 100%;
}

#banner-carousel .carousel-inner .item img {
    width: 100%;
    height: auto;
    max-height: 500px;
    object-fit: cover;
    object-position: center;
}

#banner-carousel .carousel-indicators {
    bottom: 20px;
}

#banner-carousel .carousel-indicators li {
    width: 12px;
    height: 12px;
    border-radius: 50%;
    margin: 0 5px;
    background-color: rgba(255, 255, 255, 0.5);
    border: 2px solid #fff;
}

#banner-carousel .carousel-indicators .active {
    background-color: #D10024;
}

#banner-carousel .carousel-control {
    background: none;
    width: 50px;
    text-shadow: none;
}

#banner-carousel .carousel-control .fa {
    position: absolute;
    top: 50%;
    transform: translateY(-50%);
    font-size: 30px;
    color: #fff;
    background: rgba(0, 0, 0, 0.3);
    width: 50px;
    height: 50px;
    line-height: 50px;
    border-radius: 50%;
    transition: all 0.3s ease;
}

#banner-carousel .carousel-control:hover .fa {
    background: rgba(209, 0, 36, 0.8);
}

#banner-carousel .left.carousel-control .fa {
    left: 20px;
}

#banner-carousel .right.carousel-control .fa {
    right: 20px;
}

/* Responsive adjustments */
@media (max-width: 991px) {
    #banner-carousel .carousel-inner {
        max-height: 400px;
    }
    
    #banner-carousel .carousel-inner .item img {
        max-height: 400px;
    }
}

@media (max-width: 767px) {
    #banner-carousel .carousel-inner {
        max-height: 300px;
    }
    
    #banner-carousel .carousel-inner .item img {
        max-height: 300px;
    }
    
    #banner-carousel .carousel-control .fa {
        font-size: 20px;
        width: 35px;
        height: 35px;
        line-height: 35px;
    }
    
    #banner-carousel .left.carousel-control .fa {
        left: 10px;
    }
    
    #banner-carousel .right.carousel-control .fa {
        right: 10px;
    }
}


/* Search Suggestions */
.search-suggestions {
    position: absolute;
    top: 100%;
    left: 0;
    width: calc(100% - 100px); /* Trừ đi nút search */
    background: #fff;
    z-index: 999;
    border-radius: 0 0 20px 20px;
    box-shadow: 0 4px 10px rgba(0,0,0,0.1);
    display: none;
    overflow: hidden;
    border: 1px solid #E4E7ED;
    border-top: none;
}

.search-suggestions.active {
    display: block;
}

.suggestion-item {
    display: flex;
    align-items: center;
    padding: 10px 15px;
    border-bottom: 1px solid #eee;
    cursor: pointer;
    transition: background 0.2s;
    text-decoration: none;
    color: #333 !important;
}

.suggestion-item:last-child {
    border-bottom: none;
}

.suggestion-item:hover {
    background-color: #f9f9f9;
}

.suggestion-image {
    width: 40px;
    height: 40px;
    object-fit: cover;
    margin-right: 15px;
    border-radius: 5px;
}

.suggestion-info {
    flex: 1;
    display: flex;
    flex-direction: column;
}

.suggestion-name {
    font-size: 14px;
    font-weight: 500;
    margin-bottom: 2px;
    line-height: 1.2;
}

.suggestion-price {
    font-size: 13px;
    color: #D10024 !important;
    font-weight: 700;
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
    // Search Suggestions
    const searchInput = document.getElementById('search-input');
    const suggestionsBox = document.getElementById('search-suggestions');
    let timeoutId;

    if (searchInput && suggestionsBox) {
        searchInput.addEventListener('input', function() {
            clearTimeout(timeoutId);
            const query = this.value;

            if (query.length < 2) {
                suggestionsBox.innerHTML = '';
                suggestionsBox.classList.remove('active');
                searchInput.style.borderRadius = "40px 0px 0px 40px";
                return;
            }

            timeoutId = setTimeout(() => {
                // Use the route name directly if possible, or relative path
                fetch(`{{ route('client.search.suggest') }}?query=${encodeURIComponent(query)}`)
                    .then(response => response.json())
                    .then(data => {
                        suggestionsBox.innerHTML = '';
                        if (data.length > 0) {
                            data.forEach(item => {
                                const a = document.createElement('a');
                                a.href = item.url;
                                a.className = 'suggestion-item';
                                a.innerHTML = `
                                    <img src="${item.image}" class="suggestion-image" alt="${item.name}">
                                    <div class="suggestion-info">
                                        <span class="suggestion-name">${item.name}</span>
                                        <span class="suggestion-price">${item.price}</span>
                                    </div>
                                `;
                                suggestionsBox.appendChild(a);
                            });
                            suggestionsBox.classList.add('active');
                            searchInput.style.borderRadius = "20px 0px 0px 0px";
                        } else {
                            suggestionsBox.classList.remove('active');
                            searchInput.style.borderRadius = "40px 0px 0px 40px";
                        }
                    })
                    .catch(err => console.error(err));
            }, 300);
        });

        // Hide when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !suggestionsBox.contains(e.target)) {
                suggestionsBox.classList.remove('active');
                searchInput.style.borderRadius = "40px 0px 0px 40px";
            }
        });
    }
});
</script>
