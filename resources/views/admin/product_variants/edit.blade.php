@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h3 mb-1">Chỉnh sửa biến thể</h1>
            <div class="text-muted">
                Sản phẩm: <strong>{{ $product->name }}</strong> (ID #{{ $product->id }}) · Biến thể #{{ $variant->id }}
            </div>
        </div>
        <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-secondary">← Quay lại</a>
    </div>

    @if($errors->any())
        <div class="alert alert-danger">
            <div class="fw-bold mb-1">Vui lòng kiểm tra lỗi:</div>
            <ul class="mb-0">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <form action="{{ route('admin.products.variants.update', [$product->id, $variant->id]) }}" method="POST" enctype="multipart/form-data">
        @csrf
        @method('PUT')
        @include('admin.product_variants._form', ['variant' => $variant])

        <div class="mt-4 d-flex gap-2">
            <button type="submit" class="btn btn-primary">Cập nhật</button>
            <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-outline-secondary">Huỷ</a>
        </div>
    </form>
</div>
@endsection

