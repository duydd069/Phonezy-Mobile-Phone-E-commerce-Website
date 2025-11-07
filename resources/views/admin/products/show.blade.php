@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Chi tiết sản phẩm #{{ $product->id }}</h1>
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">{{ $product->name }}</h3>
            <p class="text-muted">{{ $product->slug }}</p>
            <p><strong>Giá:</strong> {{ number_format($product->price,0,',','.') }}</p>
            <p><strong>Danh mục:</strong> {{ $product->category->name ?? '-' }}</p>
            <p><strong>Thương hiệu:</strong> {{ $product->brand->name ?? '-' }}</p>
            <p><strong>Giới tính:</strong> {{ $product->gender }}</p>
            <div class="mb-3">
                @if($product->image)
                    @if(Str::startsWith($product->image, ['http://','https://']))
                        <img src="{{ $product->image }}" style="max-width:240px">
                    @else
                        <img src="{{ asset('storage/'.$product->image) }}" style="max-width:240px">
                    @endif
                @endif
            </div>
            <p>{{ $product->description }}</p>
            <a href="{{ route('admin.products.edit',$product->id) }}" class="btn btn-warning">Sửa</a>
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">Quay lại</a>
        </div>
    </div>
</div>
@endsection
