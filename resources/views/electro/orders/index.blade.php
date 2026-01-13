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
                                    <div class="d-flex align-items-center">
                                        @if($order->items->count() > 0)
                                            @php $firstItem = $order->items->first(); @endphp
                                            @if($firstItem->product_image)
                                                <img src="{{ asset($firstItem->product_image) }}" 
                                                     alt="{{ $firstItem->product_name }}" 
                                                     class="img-thumbnail me-2" 
                                                     style="width: 50px; height: 50px; object-fit: cover; border: none; padding: 0;">
                                            @endif
                                            <div>
                                                <span class="d-block text-truncate" style="max-width: 200px;" title="{{ $firstItem->product_name }}">
                                                    {{ $firstItem->product_name }}
                                                </span>
                                                <small class="text-muted">
                                                    @if($order->items->count() > 1)
                                                        và {{ $order->items->count() - 1 }} sản phẩm khác
                                                    @else
                                                        x {{ $firstItem->quantity }}
                                                    @endif
                                                </small>
                                            </div>
                                        @else
                                            <span class="text-muted">Không có sản phẩm</span>
                                        @endif
                                    </div>
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
                                        $canCancel = $order->canBeCancelled();
                                    @endphp

                                    <button type="button"
                                        class="btn btn-sm {{ !$canCancel ? 'btn-secondary disabled' : 'btn-danger' }} btn-cancel-order mt-1"
                                        data-allowed="{{ $canCancel ? 1 : 0 }}"
                                        data-url="{{ route('client.orders.cancel', $order->id) }}"
                                        title="{{ !$canCancel ? 'Đơn hàng đã được xử lý nên không thể hủy' : 'Hủy đơn hàng' }}"
                                        {{ !$canCancel ? 'disabled' : '' }}>
                                        <i class="fas fa-times me-1"></i> Hủy đơn
                                    </button>

                                    @if(in_array($order->status, ['giao_thanh_cong', 'hoan_thanh']))
                                        @php
                                            $reviewItems = $order->items->map(fn($item) => [
                                                "id" => $item->id,
                                                "name" => $item->product_name,
                                                "image" => $item->product_image,
                                                "slug" => $item->product->slug ?? $item->product->id,
                                                "product_id" => $item->product_id
                                            ]);
                                        @endphp
                                        <button type="button" 
                                                class="btn btn-sm btn-success mt-1 btn-review-order"
                                                data-order-id="{{ $order->id }}"
                                                data-items="{{ json_encode($reviewItems) }}">
                                            <i class="fa fa-star me-1"></i> Đánh giá
                                        </button>
                                    @endif

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
    <!-- Review Modal -->
    <div class="modal fade" id="reviewModal" tabindex="-1">
        <div class="modal-dialog modal-lg modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header border-bottom-0">
                    <h5 class="modal-title">Đánh giá sản phẩm</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <div class="modal-body" id="reviewModalBody">
                    <!-- Dynamic Content -->
                </div>
            </div>
        </div>
    </div>

    <!-- Cancel Order Modal (Shared) -->
    <div class="modal fade" id="cancelOrderModal" tabindex="-1">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content shadow-lg border-0 rounded-4">

                <!-- HEADER -->
                <div class="modal-header border-0 pb-2">
                    <h5 class="modal-title fw-semibold text-danger">
                        <i class="fas fa-times-circle me-2"></i> Hủy đơn hàng
                    </h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
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
                        <select class="form-select form-control" id="cancelReason" required>
                            <option value="">-- Chọn lý do --</option>
                            <option value="Đổi ý, không muốn mua nữa">Đổi ý, không muốn mua nữa</option>
                            <option value="Đặt nhầm sản phẩm">Đặt nhầm sản phẩm</option>
                            <option value="Giá sản phẩm không phù hợp">Giá sản phẩm không phù hợp</option>
                            <option value="Thời gian giao hàng quá lâu">Thời gian giao hàng quá lâu</option>
                            <option value="Khác">Khác</option>
                        </select>
                    </div>

                    <!-- Other reason -->
                    <div class="mb-3 d-none" id="cancelReasonOtherWrapper">
                        <label class="form-label fw-semibold">
                            Lý do khác
                        </label>
                        <textarea class="form-control" id="cancelReasonOther" rows="3"
                            placeholder="Nhập lý do cụ thể..."></textarea>
                    </div>
                    
                    <div id="cancelError" class="alert alert-danger d-none small"></div>

                    <div class="alert alert-warning small">
                        <i class="fas fa-info-circle me-1"></i>
                        Đơn hàng chỉ có thể hủy khi chưa được xác nhận xử lý hoặc chưa thanh toán.
                    </div>
                </div>

                <!-- FOOTER -->
                <div class="modal-footer border-0 pt-0">
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">Đóng</button>
                    <button type="button" id="confirmCancelOrder" class="btn btn-danger px-4">
                        <i class="fas fa-check me-1"></i> Xác nhận hủy
                    </button>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')

<script>
    $(document).ready(function() {
        // Star rating HTML generator
        function getStarRatingHtml(itemId) {
            return `
                <div class="star-rating mb-3">
                    <div class="rating-group">
                        <input type="radio" id="star5-${itemId}" name="rating-${itemId}" value="5" checked />
                        <label for="star5-${itemId}" title="5 sao"><i class="fa fa-star"></i></label>
                        <input type="radio" id="star4-${itemId}" name="rating-${itemId}" value="4" />
                        <label for="star4-${itemId}" title="4 sao"><i class="fa fa-star"></i></label>
                        <input type="radio" id="star3-${itemId}" name="rating-${itemId}" value="3" />
                        <label for="star3-${itemId}" title="3 sao"><i class="fa fa-star"></i></label>
                        <input type="radio" id="star2-${itemId}" name="rating-${itemId}" value="2" />
                        <label for="star2-${itemId}" title="2 sao"><i class="fa fa-star"></i></label>
                        <input type="radio" id="star1-${itemId}" name="rating-${itemId}" value="1" />
                        <label for="star1-${itemId}" title="1 sao"><i class="fa fa-star"></i></label>
                    </div>
                </div>
                <style>
                    .rating-group { display: flex; flex-direction: row-reverse; justify-content: flex-end; gap: 5px; }
                    .rating-group input { display: none; }
                    .rating-group label { cursor: pointer; color: #ccc; font-size: 24px; transition: 0.2s; }
                    .rating-group label:hover, .rating-group label:hover ~ label, .rating-group input:checked ~ label { color: #ffc107; }
                </style>
            `;
        }

        // Open review modal
        $('.btn-review-order').click(function() {
            const items = $(this).data('items');
            const modalBody = $('#reviewModalBody');
            modalBody.empty();

            if (!items || items.length === 0) {
                modalBody.html('<p class="text-center text-muted">Không có sản phẩm nào để đánh giá.</p>');
                return;
            }

            items.forEach(function(item) {
                let imageUrl = '/electro/img/product01.png';
                if (item.image) {
                    if (item.image.startsWith('http')) {
                        imageUrl = item.image;
                    } else if (item.image.startsWith('storage/') || item.image.startsWith('/storage/')) {
                        imageUrl = item.image.startsWith('/') ? item.image : '/' + item.image;
                    } else {
                        imageUrl = '/storage/' + item.image;
                    }
                }
                
                const productUrl = `/client/p/${item.slug}`; 
                
                const itemHtml = `
                    <div class="review-item border-bottom pb-4 mb-4 product-review-container" data-product-id="${item.product_id}">
                        <div class="d-flex gap-3 mb-3 align-items-center">
                            <img src="${imageUrl}" alt="${item.name}" style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                            <div>
                                <h6 class="mb-0"><a href="${productUrl}" target="_blank" class="text-dark decoration-none">${item.name}</a></h6>
                            </div>
                        </div>
                        
                        <div class="review-form">
                            <form class="submit-review-form" data-product-id="${item.product_id}" data-product-slug="${item.slug}">
                                <div class="mb-2">
                                    <label class="form-label fw-semibold">Đánh giá của bạn</label>
                                    ${getStarRatingHtml(item.id)}
                                </div>
                                <!-- textarea removed -->
                                <div class="text-end">
                                    <button type="submit" class="btn btn-primary btn-sm rounded-pill px-4">
                                        <i class="fa fa-paper-plane me-1"></i> Gửi đánh giá
                                    </button>
                                </div>
                            </form>
                        </div>
                    </div>
                `;
                modalBody.append(itemHtml);
            });

            // Remove last border
            modalBody.find('.review-item:last-child').removeClass('border-bottom pb-4 mb-4');

            $('#reviewModal').modal('show');
        });

        // Handle form submission
        $(document).on('submit', '.submit-review-form', function(e) {
            e.preventDefault();
            const form = $(this);
            const btn = form.find('button[type="submit"]');
            const productSlug = form.data('product-slug');
            // Auto-generate content since textarea is removed
            const rating = form.find('input[type="radio"]:checked').val();
            const content = 'Đánh giá ' + rating + ' sao';

            if (!rating) {
                alert('Vui lòng chọn số sao đánh giá!');
                return;
            }

            // Disable button
            btn.prop('disabled', true).html('<i class="fa fa-spinner fa-spin"></i> Đang gửi...');

            $.ajax({
                url: `/client/comments/${productSlug}`,
                method: 'POST',
                data: {
                    _token: '{{ csrf_token() }}',
                    content: content,
                    rating: rating
                },
                success: function(response) {
                    if (response.success) {
                        form.closest('.review-item').html(`
                            <div class="alert alert-success mt-3 mb-0">
                                <i class="fa fa-check-circle me-1"></i> Đã gửi đánh giá thành công!
                            </div>
                        `);
                    } else {
                         alert(response.message || 'Có lỗi xảy ra.');
                         btn.prop('disabled', false).html('<i class="fa fa-paper-plane me-1"></i> Gửi đánh giá');
                    }
                },
                error: function(xhr) {
                    let msg = 'Có lỗi xảy ra.';
                    if (xhr.responseJSON && xhr.responseJSON.message) {
                        msg = xhr.responseJSON.message;
                    }
                    alert(msg);
                    btn.prop('disabled', false).html('<i class="fa fa-paper-plane me-1"></i> Gửi đánh giá');
                }
            });
        });
    });
</script>
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
        $('#cancelReasonOtherWrapper').removeClass('d-none');
    } else {
        $('#cancelReasonOtherWrapper').addClass('d-none');
    }
});

// Xác nhận hủy
$(document).on('click', '#confirmCancelOrder', function (){
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
    .append(`<input type="hidden" name="_token" value="${$('meta[name="csrf-token"]').attr('content')}">`)
    .append(`<input type="hidden" name="cancel_reason" value="${reason}">`)
    .appendTo('body')
    .submit();
});

</script>


@endpush
<style>
/* Button styles for consistency */
.btn-sm {
    border-radius: 4px;
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
