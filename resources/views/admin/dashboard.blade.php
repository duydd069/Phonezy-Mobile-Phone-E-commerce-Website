@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Admin Dashboard</h1>
    
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
                    <h5 class="card-title">Orders</h5>
                    <p class="card-text"><a href="{{ route('admin.orders.index') }}">Manage Orders</a></p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
