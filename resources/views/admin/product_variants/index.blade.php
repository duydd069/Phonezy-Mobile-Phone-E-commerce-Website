@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <div>
            <h1 class="h3 mb-1">Biến thể sản phẩm</h1>
            <div class="text-muted">
                Sản phẩm: <strong>{{ $product->name }}</strong> (ID #{{ $product->id }})
            </div>
        </div>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">← Danh sách sản phẩm</a>
            <a href="{{ route('admin.products.variants.create', $product->id) }}" class="btn btn-primary">+ Thêm biến thể</a>
        </div>
    </div>

    @php($statusLabels = \App\Models\ProductVariant::statusOptions())

    <div class="card">
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-bordered table-hover mb-0 align-middle">
                    <thead class="table-light">
                        <tr>
                            <th width="70">ID</th>
                            <th width="80">Ảnh</th>
                            <th>SKU</th>
                            <th>Dung lượng</th>
                            <th>Phiên bản</th>
                            <th>Màu sắc</th>
                            <th class="text-end">Giá</th>
                            <th class="text-end">Giá KM</th>
                            <th class="text-end">Tồn kho</th>
                            <th class="text-end">Đã bán</th>
                            <th>Trạng thái</th>
                            <th width="160">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($variants as $variant)
                            <tr>
                                <td>{{ $variant->id }}</td>
                                <td>
                                    @if($variant->image)
                                        <img src="{{ preg_match('/^https?:\\/\\//', $variant->image) ? $variant->image : asset('storage/' . $variant->image) }}" 
                                             alt="{{ $variant->sku }}" 
                                             style="width: 60px; height: 60px; object-fit: contain; border: 1px solid #ddd; border-radius: 4px;">
                                    @else
                                        <span class="text-muted small">—</span>
                                    @endif
                                </td>
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
                                <td>{{ $statusLabels[$variant->status] ?? $variant->status }}</td>
                                <td>
                                    <a href="{{ route('admin.products.variants.edit', [$product->id, $variant->id]) }}" class="btn btn-sm btn-warning">Sửa</a>
                                    <form action="{{ route('admin.products.variants.destroy', [$product->id, $variant->id]) }}"
                                          method="POST"
                                          class="d-inline"
                                          onsubmit="return confirm('Bạn có chắc muốn xoá biến thể này?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-danger">Xoá</button>
                                    </form>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="12" class="text-center text-muted py-4">Chưa có biến thể.</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($variants instanceof \Illuminate\Contracts\Pagination\Paginator)
            <div class="card-footer">
                {{ $variants->links() }}
            </div>
        @endif
    </div>
</div>
@endsection

