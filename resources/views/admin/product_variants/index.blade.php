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

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

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
                                <td class="text-end">
                                    <span class="editable-stock" 
                                          data-variant-id="{{ $variant->id }}"
                                          data-product-id="{{ $product->id }}"
                                          data-current-stock="{{ $variant->stock }}"
                                          style="cursor: pointer; padding: 4px 8px; border-radius: 4px; display: inline-block; min-width: 50px; text-align: center; background-color: #f8f9fa; border: 1px solid #dee2e6; transition: all 0.2s;"
                                          onmouseover="this.style.backgroundColor='#e9ecef'; this.style.borderColor='#adb5bd';"
                                          onmouseout="this.style.backgroundColor='#f8f9fa'; this.style.borderColor='#dee2e6';"
                                          title="Click để chỉnh sửa số lượng tồn kho">
                                        {{ $variant->stock ?? 0 }}
                                    </span>
                                </td>
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Xử lý inline editing cho số lượng tồn kho
    document.querySelectorAll('.editable-stock').forEach(function(element) {
        element.addEventListener('click', function() {
            const variantId = this.getAttribute('data-variant-id');
            const productId = this.getAttribute('data-product-id');
            const currentStock = parseInt(this.getAttribute('data-current-stock')) || 0;
            const originalText = this.textContent.trim();
            
            // Tạo input field
            const input = document.createElement('input');
            input.type = 'number';
            input.min = '0';
            input.step = '1';
            input.value = currentStock;
            input.className = 'form-control form-control-sm';
            input.style.cssText = 'width: 80px; text-align: center; display: inline-block; border: 2px solid #0d6efd; box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);';
            
            // Lưu context
            const self = this;
            const originalDisplay = this.style.display;
            
            // Thay thế text bằng input
            this.innerHTML = '';
            this.appendChild(input);
            this.style.display = 'inline-block';
            input.focus();
            input.select();
            
            // Hàm khôi phục
            function restore() {
                self.innerHTML = originalText;
                self.setAttribute('data-current-stock', input.value);
            }
            
            // Xử lý khi blur hoặc Enter
            function saveStock() {
                const newStock = parseInt(input.value) || 0;
                
                if (newStock === currentStock) {
                    restore();
                    return;
                }
                
                // Disable input trong khi đang lưu
                input.disabled = true;
                self.innerHTML = '<i class="fa fa-spinner fa-spin"></i>';
                
                // Gửi AJAX request
                fetch(`/admin/products/${productId}/variants/${variantId}/stock`, {
                    method: 'PATCH',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.getAttribute('content') || '{{ csrf_token() }}',
                        'Accept': 'application/json',
                        'X-Requested-With': 'XMLHttpRequest'
                    },
                    body: JSON.stringify({
                        stock: newStock
                    })
                })
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Lỗi từ server');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    if (data.success) {
                        self.innerHTML = data.stock;
                        self.setAttribute('data-current-stock', data.stock);
                        
                        // Cập nhật style sau khi lưu thành công
                        self.style.backgroundColor = '#d1e7dd';
                        self.style.borderColor = '#badbcc';
                        setTimeout(() => {
                            self.style.backgroundColor = '#f8f9fa';
                            self.style.borderColor = '#dee2e6';
                        }, 1000);
                        
                        // Cập nhật status nếu có thay đổi
                        // Tìm cột status (cột thứ 11, trước cột "Thao tác")
                        const row = self.closest('tr');
                        const statusCell = row.querySelectorAll('td')[10]; // Cột status là cột thứ 11 (index 10)
                        if (statusCell && data.status_label) {
                            const statusLabels = @json(\App\Models\ProductVariant::statusOptions());
                            if (data.status === 'available') {
                                statusCell.innerHTML = '<span class="badge bg-success">' + statusLabels.available + '</span>';
                            } else if (data.status === 'out_of_stock') {
                                statusCell.innerHTML = '<span class="badge bg-warning">' + statusLabels.out_of_stock + '</span>';
                            } else if (data.status === 'discontinued') {
                                statusCell.innerHTML = '<span class="badge bg-secondary">' + statusLabels.discontinued + '</span>';
                            }
                        }
                        
                        // Hiển thị thông báo thành công
                        if (typeof showToast === 'function') {
                            showToast(data.message || 'Đã cập nhật số lượng tồn kho', 'success');
                        } else {
                            // Fallback nếu không có showToast
                            const toast = document.createElement('div');
                            toast.className = 'alert alert-success alert-dismissible fade show position-fixed';
                            toast.style.cssText = 'top: 20px; right: 20px; z-index: 9999; min-width: 300px;';
                            toast.innerHTML = '<strong>Thành công!</strong> ' + (data.message || 'Đã cập nhật số lượng tồn kho') + 
                                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>';
                            document.body.appendChild(toast);
                            setTimeout(() => toast.remove(), 3000);
                        }
                    } else {
                        throw new Error(data.message || 'Có lỗi xảy ra');
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    self.innerHTML = originalText;
                    self.style.backgroundColor = '#f8d7da';
                    self.style.borderColor = '#f5c2c7';
                    setTimeout(() => {
                        self.style.backgroundColor = '#f8f9fa';
                        self.style.borderColor = '#dee2e6';
                    }, 2000);
                    alert('Lỗi: ' + (error.message || 'Không thể cập nhật số lượng tồn kho'));
                });
            }
            
            // Xử lý khi nhấn Enter
            input.addEventListener('keydown', function(e) {
                if (e.key === 'Enter') {
                    e.preventDefault();
                    saveStock();
                } else if (e.key === 'Escape') {
                    e.preventDefault();
                    restore();
                }
            });
            
            // Xử lý khi blur
            input.addEventListener('blur', function() {
                setTimeout(saveStock, 200); // Delay để cho phép click vào button khác
            });
        });
    });
});
</script>
@endpush
@endsection

