@extends('electro.layout')

@section('content')
<div class="container py-4">
    <h3 class="mb-4">Danh mục: {{ $category->name }}</h3>

    <div class="row">
        @forelse ($products as $product)
            <div class="col-md-3 mb-3">
                <div class="card h-100">
                    <img src="{{ asset('uploads/' . $product->image) }}" class="card-img-top">

                    <div class="card-body">
                        <h6 class="card-title">{{ $product->name }}</h6>
                        <p>{{ number_format($product->price) }}đ</p>
                        <a href="{{ route('client.product.show', $product->id) }}" class="btn btn-primary w-100">
                            Xem chi tiết
                        </a>
                    </div>
                </div>
            </div>
        @empty
            <p>Không có sản phẩm nào.</p>
        @endforelse
    </div>

    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>
@endsection
