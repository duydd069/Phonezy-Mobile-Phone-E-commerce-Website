@extends('layouts.app')

@section('content')
<div class="container">
    <h1>Chi tiết sản phẩm #{{ $product->id }}</h1>
    <div class="card">
        <div class="card-body">
            <h3 class="card-title">{{ $product->name }}</h3>
            <p class="text-muted">{{ $product->slug }}</p>
            @php
                $firstVariant = $product->variants->first();
                $displayPrice = $firstVariant ? ($firstVariant->price_sale ?? $firstVariant->price) : 0;
            @endphp
            <p><strong>Giá (qua biến thể):</strong> {{ number_format($displayPrice,0,',','.') }} </p>
            <p><strong>Danh mục:</strong> {{ $product->category->name ?? '-' }}</p>
            <p><strong>Thương hiệu:</strong> {{ $product->brand->name ?? '-' }}</p>
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
            <a href="{{ route('admin.products.variants.index', $product->id) }}" class="btn btn-primary">Quản lý biến thể</a>
        </div>
    </div>

    <div class="card mt-4">
        <div class="card-header d-flex justify-content-between align-items-center">
            <h5 class="mb-0">Biến thể</h5>
            <a href="{{ route('admin.products.variants.create', $product->id) }}" class="btn btn-sm btn-outline-primary">+ Thêm biến thể</a>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-striped mb-0">
                    <thead>
                        <tr>
                            <th width="70">ID</th>
                            <th>SKU</th>
                            <th>Dung lượng</th>
                            <th>Phiên bản</th>
                            <th>Màu sắc</th>
                            <th class="text-end">Giá</th>
                            <th class="text-end">Giá KM</th>
                            <th class="text-end">Tồn kho</th>
                            <th class="text-end">Đã bán</th>
                            <th>Trạng thái</th>
                            <th width="120">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @php($statusOptions = \App\Models\ProductVariant::statusOptions())
                        @forelse($product->variants as $variant)
                            <tr>
                                <td>{{ $variant->id }}</td>
                                <td>
                                    <div class="fw-semibold">{{ $variant->sku }}</div>
                                    @if($variant->barcode)
                                        <div class="text-muted small">Barcode: {{ $variant->barcode }}</div>
                                    @endif
                                </td>
                                <td>
                                    @if($variant->storage)
                                        <span class="badge bg-secondary">{{ $variant->storage->storage }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($variant->version)
                                        <span class="badge bg-info">{{ $variant->version->name }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($variant->color)
                                        <span class="badge" style="background-color: {{ $variant->color->hex_code ?? '#6c757d' }}; color: white;">{{ $variant->color->name }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ number_format($variant->price, 0, ',', '.') }}</td>
                                <td class="text-end">
                                    @if($variant->price_sale)
                                        <span class="text-danger">{{ number_format($variant->price_sale, 0, ',', '.') }}</span>
                                    @else
                                        <span class="text-muted">—</span>
                                    @endif
                                </td>
                                <td class="text-end">{{ $variant->stock }}</td>
                                <td class="text-end">{{ $variant->sold }}</td>
                                <td>{{ $statusOptions[$variant->status] ?? $variant->status }}</td>
                                <td>
                                    <a href="{{ route('admin.products.variants.edit', [$product->id, $variant->id]) }}" class="btn btn-sm btn-outline-secondary">Sửa</a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="11" class="text-center text-muted py-3">Chưa có biến thể nào.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
