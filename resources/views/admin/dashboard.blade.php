@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Trang Quản Trị</h1>
    
    <div class="row mt-4">
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Sản phẩm</h5>
                    <p class="card-text"><a href="{{ route('admin.products.index') }}">Quản lý sản phẩm</a></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Thương hiệu</h5>
                    <p class="card-text"><a href="{{ route('admin.brands.index') }}">Quản lý thương hiệu</a></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Danh mục</h5>
                    <p class="card-text"><a href="{{ route('admin.categories.index') }}">Quản lý danh mục</a></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Người dùng</h5>
                    <p class="card-text"><a href="{{ route('admin.users.index') }}">Quản lý người dùng</a></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card">
                <div class="card-body">
                    <h5 class="card-title">Đơn Hàng</h5>
                    <p class="card-text"><a href="{{ route('admin.orders.index') }}">Quản lý Đơn Hàng</a></p>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card bg-primary text-white">
                <div class="card-body">
                    <h5 class="card-title text-white">
                        <i class="bi bi-graph-up me-2"></i>Báo Cáo Doanh Thu
                    </h5>
                    <p class="card-text">
                        <a href="{{ route('admin.revenue.index') }}" class="text-white text-decoration-none">
                            Xem báo cáo doanh thu
                        </a>
                    </p>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
