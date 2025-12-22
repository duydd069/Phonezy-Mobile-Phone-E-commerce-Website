@extends('electro.layout')

@section('title', 'Đơn hàng của tôi')

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            @include('electro.account._sidebar')
            
            <div class="col-md-9">
                <div class="section-title">
                    <h3 class="title">Đơn hàng của tôi</h3>
                </div>

        @if($orders->isEmpty())
            <div class="alert alert-info text-center">
                <h4>Bạn chưa có đơn hàng nào</h4>
                <p>Hãy bắt đầu mua sắm để tạo đơn hàng đầu tiên!</p>
                <a href="{{ route('client.index') }}" class="btn btn-primary mt-3">
                    <i class="fa fa-shopping-bag"></i> Mua sắm ngay
                </a>
            </div>
        @else
            <!-- Filter by status -->
            <div class="mb-4">
                <form method="GET" action="{{ route('client.orders.index') }}" class="d-flex gap-2">
                    <select name="status" class="form-control" style="max-width: 250px;" onchange="this.form.submit()">
                        <option value="">Tất cả trạng thái</option>
                        @foreach(\App\Models\Order::getAvailableStatuses() as $statusValue => $statusLabel)
                            <option value="{{ $statusValue }}" {{ request('status') == $statusValue ? 'selected' : '' }}>
                                {{ $statusLabel }}
                            </option>
                        @endforeach
                    </select>
                </form>
            </div>

            <div class="table-responsive">
                <table class="table table-bordered table-hover">
                    <thead class="thead-light">
                        <tr>
                            <th>Mã đơn hàng</th>
                            <th>Ngày đặt</th>
                            <th>Sản phẩm</th>
                            <th>Tổng tiền</th>
                            <th>Trạng thái</th>
                            <th>Thanh toán</th>
                            <th>Hành động</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($orders as $order)
                            <tr>
                                <td>
                                    <strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong>
                                </td>
                                <td>
                                    {{ $order->created_at->format('d/m/Y H:i') }}
                                </td>
                                <td>
                                    <small>
                                        {{ $order->items->count() }} sản phẩm
                                        @if($order->items->count() > 0)
                                            <br>
                                            <span class="text-muted">{{ $order->items->first()->product_name }}</span>
                                            @if($order->items->count() > 1)
                                                <span class="text-muted">và {{ $order->items->count() - 1 }} sản phẩm khác</span>
                                            @endif
                                        @endif
                                    </small>
                                </td>
                                <td>
                                    <strong>{{ number_format($order->total, 0, ',', '.') }} ₫</strong>
                                    @if($order->discount_amount > 0)
                                        <br>
                                        <small class="text-success">
                                            Đã giảm: {{ number_format($order->discount_amount, 0, ',', '.') }} ₫
                                        </small>
                                    @endif
                                </td>
                                <td>
                                    <span class="badge {{ $order->status_badge_class }}">{{ $order->status_label }}</span>
                                </td>
                                <td>
                                    @if($order->payment_status == 1)
                                        <span class="badge bg-success">Đã thanh toán</span>
                                    @else
                                        <span class="badge bg-warning">Chưa thanh toán</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('client.orders.show', $order->id) }}" 
                                    class="btn btn-sm btn-primary">
                                        <i class="fa fa-eye me-1"></i> Xem chi tiết
                                    </a>

                                    @php
                                        $isPending = $order->isPending();
                                    @endphp

                                    <button type="button"
                                        class="btn btn-sm btn-cancel-order mt-1 {{ !$isPending ? 'btn-disabled' : '' }}"
                                        data-allowed="{{ $isPending ? 1 : 0 }}"
                                        data-url="{{ route('client.client.orders.cancel', $order->id) }}"
                                        title="{{ !$isPending ? 'Đơn hàng đã được xử lý nên không thể hủy' : 'Hủy đơn hàng' }}">
                                        <i class="fas fa-times me-1"></i> Hủy đơn
                                    </button>
                                    <div class="modal fade" id="cancelOrderModal" tabindex="-1">
                                    <div class="modal-dialog modal-dialog-centered">
                                        <div class="modal-content shadow-lg border-0 rounded-4">

                                            <!-- HEADER -->
                                            <div class="modal-header border-0 pb-2">
                                                <h5 class="modal-title fw-semibold text-danger">
                                                    <i class="fas fa-times-circle me-2"></i> Hủy đơn hàng
                                                </h5>
                                                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                                            </div>

                                            <!-- BODY -->
                                            <div class="modal-body pt-1">
                                                <p class="text-muted mb-3">
                                                    Vui lòng cho chúng tôi biết lý do bạn muốn hủy đơn hàng.
                                                </p>

                                                <!-- Reason select -->
                                                <div class="mb-3">
                                                    <label class="form-label fw-semibold">
                                                        Lý do hủy đơn <span class="text-danger">*</span>
                                                    </label>
                                                    <select class="form-select" id="cancelReason" required>
                                                        <option value="">-- Chọn lý do --</option>
                                                        <option value="Đổi ý, không muốn mua nữa">Đổi ý, không muốn mua nữa</option>
                                                        <option value="Đặt nhầm sản phẩm">Đặt nhầm sản phẩm</option>
                                                        <option value="Giá sản phẩm không phù hợp">Giá sản phẩm không phù hợp</option>
                                                        <option value="Thời gian giao hàng quá lâu">Thời gian giao hàng quá lâu</option>
                                                        <option value="Khác">Khác</option>
                                                    </select>
                                                </div>

                                                <!-- Other reason -->
                                                <div class="mb-3 d-none" id="otherReasonWrapper">
                                                    <label class="form-label fw-semibold">
                                                        Lý do khác
                                                    </label>
                                                    <textarea class="form-control" rows="3"
                                                        placeholder="Nhập lý do cụ thể..."></textarea>
                                                </div>

                                                <div class="alert alert-warning small">
                                                    <i class="fas fa-info-circle me-1"></i>
                                                    Đơn hàng chỉ có thể hủy khi chưa được xác nhận xử lý.
                                                </div>
                                            </div>

                                            <!-- FOOTER -->
                                            <div class="modal-footer border-0 pt-0">
                                                <a href="{{ route('client.orders.index') }}" class="btn btn-secondary">
                                                        <i class="fa fa-arrow-left"></i> Quay lại </a>
                                                <button type="submit" class="btn btn-danger px-4">
                                                    <i class="fas fa-check me-1"></i> Xác nhận hủy
                                                </button>
                                            </div>

                                        </div>
                                    </div>
                                </div>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                                                                   
                <!-- Pagination -->
            <div class="mt-4">
                {{ $orders->links() }}
            </div>
        @endif

    </div>
</div>
</div>
</div>
@endsection

@push('scripts')
<script>
let cancelUrl = null;

$(document).on('click', '.btn-cancel-order', function () {
    const allowed = $(this).data('allowed');

    if (!allowed) {
        alert('Đơn hàng đã được xử lý nên không thể hủy.');
        return;
    }

    cancelUrl = $(this).data('url');

    $('#cancelReason').val('');
    $('#cancelReasonOther').addClass('d-none').val('');
    $('#cancelError').addClass('d-none');

    $('#cancelOrderModal').modal('show');
});

// Hiện textarea khi chọn "Khác"
$('#cancelReason').on('change', function () {
    if ($(this).val() === 'Khác') {
        $('#cancelReasonOther').removeClass('d-none');
    } else {
        $('#cancelReasonOther').addClass('d-none');
    }
});

// Xác nhận hủy
$('#confirmCancelOrder').on('click', function () {
    let reason = $('#cancelReason').val();

    if (!reason) {
        $('#cancelError').removeClass('d-none');
        return;
    }

    if (reason === 'Khác') {
        reason = $('#cancelReasonOther').val().trim();
        if (!reason) {
            $('#cancelError').text('Vui lòng nhập lý do cụ thể').removeClass('d-none');
            return;
        }
    }

    $('<form>', {
        method: 'POST',
        action: cancelUrl
    })
    .append('@csrf')
    .append(`<input type="hidden" name="cancel_reason" value="${reason}">`)
    .appendTo('body')
    .submit();
});

$('#cancelReason').on('change', function () {
    if ($(this).val() === 'Khác') {
        $('#otherReasonWrapper').removeClass('d-none');
    } else {
        $('#otherReasonWrapper').addClass('d-none');
    }
});
</script>


@endpush
<style>
    .btn-cancel-order {
    border-color: #dc3545;
    color: #dc3545;
    background-color: transparent;
    transition: all 0.2s ease-in-out;
}

.btn-cancel-order:hover {
    background-color: #dc3545;
    color: #fff;
    transform: translateY(-1px);
}

.btn-cancel-order.btn-disabled {
    opacity: 0.45;
    cursor: pointer;
    background-color: transparent;
    color: #dc3545;
    border-color: #dc3545;
}

.btn-cancel-order.btn-disabled:hover {
    background-color: transparent;
    color: #dc3545;
    transform: none;
}

.btn-cancel-order i {
    font-size: 0.9rem;
    vertical-align: middle;
}
.modal-content {
    animation: fadeInScale .2s ease-in-out;
}

@keyframes fadeInScale {
    from {
        opacity: 0;
        transform: scale(0.95);
    }
    to {
        opacity: 1;
        transform: scale(1);
    }
}

.form-select:focus,
.form-control:focus {
    box-shadow: 0 0 0 0.15rem rgba(220, 53, 69, .25);
    border-color: #dc3545;
}

</style>
