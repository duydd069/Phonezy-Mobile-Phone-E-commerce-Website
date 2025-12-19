<!-- Account Sidebar -->
<div class="col-md-3">
    <div class="account-sidebar">
        <div class="section-title">
            <h4 class="title">Tài khoản</h4>
        </div>
        <ul class="list-unstyled account-menu">
            <li class="{{ request()->routeIs('client.account.index') ? 'active' : '' }}">
                <a href="{{ route('client.account.index') }}">
                    <i class="fa fa-user"></i> Thông tin tài khoản
                </a>
            </li>
            <li class="{{ request()->routeIs('client.orders.*') ? 'active' : '' }}">
                <a href="{{ route('client.orders.index') }}">
                    <i class="fa fa-shopping-bag"></i> Đơn hàng của tôi
                </a>
            </li>
            <li class="{{ request()->routeIs('client.coupons.*') ? 'active' : '' }}">
                <a href="{{ route('client.coupons.index') }}">
                    <i class="fa fa-tag"></i> Mã khuyến mãi
                </a>
            </li>
            <li>
                <a href="#" onclick="event.preventDefault(); document.getElementById('logout-form').submit();">
                    <i class="fa fa-sign-out"></i> Đăng xuất
                </a>
                <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display:none;">
                    @csrf
                </form>
            </li>
        </ul>
    </div>
</div>

<style>
.account-sidebar {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
    margin-bottom: 20px;
}

.account-menu li {
    margin-bottom: 10px;
}

.account-menu li a {
    display: block;
    padding: 10px 15px;
    color: #333;
    text-decoration: none;
    border-radius: 3px;
    transition: all 0.3s;
}

.account-menu li a:hover {
    background: #e9ecef;
    color: #007bff;
}

.account-menu li.active a {
    background: #007bff;
    color: #fff;
}

.account-menu li a i {
    margin-right: 8px;
    width: 20px;
}
</style>
