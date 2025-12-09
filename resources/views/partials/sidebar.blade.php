      <!--begin::Sidebar-->
      <aside class="app-sidebar bg-body-secondary shadow" data-bs-theme="dark">
        <!--begin::Sidebar Brand-->
        <div class="sidebar-brand">
          <!--begin::Brand Link-->
          <a href="{{ url('/') }}" class="brand-link">
            <!--begin::Brand Image-->
            <img
              src="{{ asset('dist/assets/img/AdminLTELogo.png') }}"
              alt="AdminLTE Logo"
              class="brand-image opacity-75 shadow"
            />
            <!--end::Brand Image-->
            <!--begin::Brand Text-->
            <span class="brand-text fw-light">AdminLTE 4</span>
            <!--end::Brand Text-->
          </a>
          <!--end::Brand Link-->
        </div>
        <!--end::Sidebar Brand-->
        <!--begin::Sidebar Wrapper-->
        <div class="sidebar-wrapper">
          <nav class="mt-2">
            <!--begin::Sidebar Menu-->
            <ul
              class="nav sidebar-menu flex-column"
              data-lte-toggle="treeview"
              role="navigation"
              aria-label="Main navigation"
              data-accordion="false"
              id="navigation"
            >
              
              <li class="nav-item">
                <a href="{{ route('admin.dashboard') }}" class="nav-link">
                  <i class="nav-icon bi bi-speedometer2"></i>
                  <p>Bảng điều khiển</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{ route('admin.revenue.index') }}" class="nav-link">
                  <i class="nav-icon bi bi-graph-up"></i>
                  <p>Báo Cáo Doanh Thu</p>
                </a>
              </li>

              <li class="nav-item">
                <a href="{{ route('admin.orders.index') }}" class="nav-link">
                  <i class="nav-icon bi bi-cart-check"></i>
                  <p>Đơn Hàng</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{ route('admin.products.index') }}" class="nav-link">
                  <i class="nav-icon bi bi-box"></i>
                  <p>Sản phẩm</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{ route('admin.brands.index') }}" class="nav-link">
                  <i class="nav-icon bi bi-award"></i>
                  <p>Thương hiệu</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{ route('admin.categories.index') }}" class="nav-link">
                  <i class="nav-icon bi bi-grid"></i>
                  <p>Danh mục</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{ route('admin.colors.index') }}" class="nav-link">
                  <i class="nav-icon bi bi-palette"></i>
                  <p>Màu sắc</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{ route('admin.storages.index') }}" class="nav-link">
                  <i class="nav-icon bi bi-hdd"></i>
                  <p>Dung lượng</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{ route('admin.versions.index') }}" class="nav-link">
                  <i class="nav-icon bi bi-layers"></i>
                  <p>Phiên bản</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{ route('admin.coupons.index') }}" class="nav-link">
                  <i class="nav-icon bi bi-tag"></i>
                  <p>Khuyến mãi</p>
                </a>
              </li>
              
              <li class="nav-item">
                <a href="{{ route('admin.users.index') }}" class="nav-link">
                  <i class="nav-icon bi bi-people"></i>
                  <p>Người dùng</p>
                </a>
              </li>
            </ul>
            <!--end::Sidebar Menu-->
          </nav>
        </div>
        <!--end::Sidebar Wrapper-->
      </aside>
      <!--end::Sidebar-->

