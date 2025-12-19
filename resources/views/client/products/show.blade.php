@extends('layouts.app')

@section('content')

<div class="container py-4">

    <h1>{{ $product->name }}</h1>

    {{-- Lấy variant đầu tiên để add vào giỏ --}}
    @php
        $variant = $product->variants->first();
    @endphp

    @if($variant)
    <form action="{{ route('cart.add') }}" method="POST" class="mt-3">
        @csrf
        <input type="hidden" name="product_variant_id" value="{{ $variant->id }}">

        <label for="qty" class="mb-1">Số lượng</label>
        <div class="input-group" style="max-width: 180px;">
            <input type="number" name="quantity" value="1" min="1" id="qty" class="form-control">
            <button type="submit" class="btn btn-primary">Thêm vào giỏ</button>
        </div>
    </form>
    @endif

</div>

@endsection
