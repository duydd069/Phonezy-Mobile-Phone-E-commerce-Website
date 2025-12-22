@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">
            <i class="fa fa-shopping-cart"></i> Quản Lý Đơn Hàng
        </h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Status Filter Buttons --}}
    @php
        $allStatuses = \App\Models\Order::getAvailableStatuses();
        $currentStatus = request('status');
    @endphp
    
    <div class="mb-3">
        <div class="btn-group flex-wrap" role="group">
            <a href="{{ route('admin.orders.index') }}" 
               class="btn btn-sm {{ !$currentStatus ? 'btn-primary' : 'btn-outline-primary' }}">
                Tất cả
            </a>
            @foreach($allStatuses as $statusKey => $statusLabel)
                <a href="{{ route('admin.orders.index', ['status' => $statusKey]) }}" 
                   class="btn btn-sm {{ $currentStatus === $statusKey ? 'btn-primary' : 'btn-outline-primary' }}">
                    {{ $statusLabel }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 mb-3">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <div class="col-auto">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" 
                   placeholder="Nhập mã đơn hàng hoặc email..." style="min-width: 300px;">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search"></i> Tìm kiếm
            </button>
            @if(request('q'))
                <a href="{{ route('admin.orders.index', ['status' => request('status')]) }}" class="btn btn-outline-secondary">
                    <i class="fa fa-times"></i> Xóa
                </a>
            @endif
        </div>
    </form>

    {{-- Order Count --}}
    <div class="mb-2">
        <strong>Tổng: {{ number_format($orders->total()) }} Đơn hàng</strong>
    </div>

    {{-- Orders Table --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th style="width: 300px;">Sản phẩm</th>
                    <th style="width: 150px;">Tổng đơn hàng</th>
                    <th style="width: 150px;">Trạng thái</th>
                    <th style="width: 150px;">Trạng thái vận chuyển giao hàng</th>
                    <th style="width: 150px;">Trạng thái vận chuyển hoàn hàng</th>
                    <th style="width: 200px;">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr id="order-{{ $order->id }}">
                        <td>
                            <div class="mb-1">
                                <strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong>
                            </div>
                            @foreach($order->items->take(2) as $item)
                                <div class="d-flex align-items-center mb-1">
                                    @if($item->product_image)
                                        <img src="{{ asset('storage/' . $item->product_image) }}" 
                                             alt="{{ $item->product_name }}" 
                                             style="width: 40px; height: 40px; object-fit: cover; margin-right: 8px;">
                                    @endif
                                    <div style="font-size: 13px;">
                                        {{ $item->product_name }} 
                                        <span class="text-muted">(x{{ $item->quantity }})</span>
                                    </div>
                                </div>
                            @endforeach
                            @if($order->items->count() > 2)
                                <small class="text-muted">+ {{ $order->items->count() - 2 }} sản phẩm khác</small>
                            @endif
                        </td>
                        <td>
                            <strong style="color: #F7941D;">
                                {{ number_format($order->total, 0, ',', '.') }}₫
                            </strong>
                        </td>
                        <td>
                            <span class="badge {{ $order->status_badge_class }} order-status-badge" 
                                  style="font-size: 12px; padding: 6px 10px;">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td>
                            <span class="order-shipping-status">
                                {{ $order->shipping_status_label }}
                            </span>
                        </td>
                        <td>
                            <span class="text-muted">-</span>
                        </td>
                        <td>
                            <div class="btn-group-vertical btn-group-sm order-actions">
                                {{-- View Button - Always Available --}}
                                <a href="{{ route('admin.orders.show', $order->id) }}" 
                                   class="btn btn-sm btn-info mb-1">
                                    <i class="fa fa-eye"></i> Xem
                                </a>

                                @if($order->status === 'cho_xac_nhan')
                                    {{-- Pending Confirmation --}}
                                    <button type="button" class="btn btn-sm btn-success mb-1 update-status-btn" 
                                            data-order-id="{{ $order->id }}" 
                                            data-status="da_xac_nhan">
                                        <i class="fa fa-check"></i> Xác nhận
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger cancel-btn" 
                                            data-order-id="{{ $order->id }}" 
                                            data-status="da_huy">
                                        <i class="fa fa-times"></i> Hủy
                                    </button>

                                @elseif($order->status === 'da_xac_nhan')
                                    {{-- Confirmed --}}
                                    <button type="button" class="btn btn-sm btn-primary mb-1 update-status-btn" 
                                            data-order-id="{{ $order->id }}" 
                                            data-status="chuan_bi_hang">
                                        <i class="fa fa-box"></i> Chuẩn bị
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger cancel-btn" 
                                            data-order-id="{{ $order->id }}" 
                                            data-status="da_huy">
                                        <i class="fa fa-times"></i> Hủy
                                    </button>

                                @elseif($order->status === 'chuan_bi_hang')
                                    {{-- Preparing --}}
                                    <button type="button" class="btn btn-sm btn-warning mb-1 update-status-btn" 
                                            data-order-id="{{ $order->id }}" 
                                            data-status="dang_giao_hang">
                                        <i class="fa fa-truck"></i> Giao hàng
                                    </button>

                                @elseif($order->status === 'dang_giao_hang')
                                    {{-- Shipping --}}
                                    <button type="button" class="btn btn-sm btn-success mb-1 update-status-btn" 
                                            data-order-id="{{ $order->id }}" 
                                            data-status="giao_thanh_cong">
                                        <i class="fa fa-check-circle"></i> Đã giao
                                    </button>
                                    <button type="button" class="btn btn-sm btn-danger update-status-btn" 
                                            data-order-id="{{ $order->id }}" 
                                            data-status="giao_that_bai">
                                        <i class="fa fa-exclamation-circle"></i> Thất bại
                                    </button>
                                @endif
                            </div>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="6" class="text-center text-muted py-3">Không tìm thấy đơn hàng nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    @endif
</div>

{{-- AJAX Status Update Script --}}
@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    // Handle all status update buttons
    document.querySelectorAll('.update-status-btn, .cancel-btn').forEach(button => {
        // Skip if handler already attached
        if (button.hasAttribute('data-handler-attached')) {
            return;
        }
        
        // Mark as handled
        button.setAttribute('data-handler-attached', 'true');
        
        button.addEventListener('click', function() {
            const orderId = this.dataset.orderId;
            const newStatus = this.dataset.status;
            const row = document.getElementById(`order-${orderId}`);
            
            if (!confirm('Bạn có chắc chắn muốn cập nhật trạng thái đơn hàng?')) {
                return;
            }

            // Disable button during request
            this.disabled = true;
            const originalText = this.innerHTML;
            this.innerHTML = '<i class="fa fa-spinner fa-spin"></i> Đang xử lý...';

            // Send AJAX request
            fetch(`/admin/orders/${orderId}/quick-status`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    'Accept': 'application/json',
                },
                body: JSON.stringify({
                    status: newStatus
                })
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Reload page to show session flash message
                    window.location.reload();
                } else {
                    alert('Có lỗi xảy ra, vui lòng thử lại!');
                    this.disabled = false;
                    this.innerHTML = originalText;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Có lỗi xảy ra, vui lòng thử lại!');
                this.disabled = false;
                this.innerHTML = originalText;
            });
        });
    });
});
</script>
@endpush
@endsection