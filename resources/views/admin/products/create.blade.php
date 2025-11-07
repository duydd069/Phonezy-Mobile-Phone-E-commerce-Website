@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Thêm sản phẩm</h1>

    @if ($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold">Vui lòng kiểm tra lỗi:</div>
            <ul class="mb-0">
                @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data" class="row g-3">
        @csrf

        <div class="col-md-8">
            <label class="form-label">Tên sản phẩm *</label>
            <input type="text" name="name" class="form-control" value="{{ old('name') }}" required>
        </div>

        <div class="col-md-4">
            <label class="form-label">Giá (VND) *</label>
            <input type="number" name="price" min="0" step="1" class="form-control" value="{{ old('price') }}" required>
        </div>

        <div class="col-md-6">
            <label class="form-label">Danh mục *</label>
            <select name="category_id" class="form-select" required>
                <option value="">-- Chọn danh mục --</option>
                @foreach($categories as $c)
                    <option value="{{ $c->id }}" @selected(old('category_id')==$c->id)>{{ $c->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-6">
            <label class="form-label">Thương hiệu *</label>
            <select name="brand_id" class="form-select" required>
                <option value="">-- Chọn thương hiệu --</option>
                @foreach($brands as $b)
                    <option value="{{ $b->id }}" @selected(old('brand_id')==$b->id)>{{ $b->name }}</option>
                @endforeach
            </select>
        </div>

        <div class="col-md-4">
            <label class="form-label">Giới tính</label>
            <select name="gender" class="form-select">
                <option value="unisex" @selected(old('gender')=='unisex')>unisex</option>
                <option value="male"   @selected(old('gender')=='male')>male</option>
                <option value="female" @selected(old('gender')=='female')>female</option>
            </select>
        </div>

        <div class="col-md-8">
            <label class="form-label">Ảnh đại diện</label>
            <input type="file" name="image" class="form-control">
            
        </div>

        <div class="col-12">
            <label class="form-label">Mô tả</label>
            <textarea name="description" rows="5" class="form-control">{{ old('description') }}</textarea>
        </div>

        <div class="col-12 d-flex gap-2">
            <button class="btn btn-primary">Lưu</button>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Huỷ</a>
        </div>
    </form>
</div>
@endsection
