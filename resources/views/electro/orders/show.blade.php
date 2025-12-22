@extends('electro.layout')

@section('title', 'Chi tiết đơn hàng #' . $order->id)

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            @include('electro.account._sidebar')
            
            <div class="col-md-9">
                <div class="section-title">
                    <h3 class="title">Chi tiết đơn hàng #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h3>
                </div>

                <div class="row">
            <div class="col-md-8">
                <!-- Order Information -->
                <div class="order-details">
                    <div class="section-title">
                        <h4 class="title">Thông tin đơn hàng</h4>
                    </div>
                    
                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Mã đơn hàng:</strong><br>
                            <span>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>
                        <div class="col-md-6">
                            <strong>Ngày đặt hàng:</strong><br>
                            <span>{{ $order->created_at->format('d/m/Y H:i') }}</span>
                        </div>
                    </div>

                    <div class="row mb-3">
                        <div class="col-md-6">
                            <strong>Trạng thái:</strong><br>
                            <span class="badge {{ $order->status_badge_class }}">{{ $order->status_label }}</span>
                            
                            {{-- Check if return is rejected - show special badge --}}
                            @php
                                $rejectedReturn = $order->returns->where('status', 'Từ chối')->sortByDesc('created_at')->first();
                                $hasActiveReturn = $order->returns->whereIn('status', ['Chưa giải quyết', 'Thông qua', 'Đang vận chuyển', 'Đã nhận', 'Đã hoàn tiền'])->count() > 0;
                            @endphp
                            @if($rejectedReturn)
                                <div class="mt-2">
                                    <span class="badge bg-danger">Từ chối hoàn hàng</span>
                                </div>
                            @endif
                            
                            {{-- Return Button - Only show if NO rejected return AND no active return AND can be returned --}}
                            @if(!$rejectedReturn && !$hasActiveReturn && $order->canBeReturned())
                                <div class="mt-2">
                                    <a href="{{ route('client.orders.return.create', $order) }}" class="btn btn-sm btn-warning">
                                        <i class="fa fa-undo"></i> Hoàn hàng
                                    </a>
                                </div>
                            @endif
                        </div>
                        <div class="col-md-6">
                            <strong>Thanh toán:</strong><br>
                            <span class="badge {{ $order->payment_status == 1 ? 'bg-success' : 'bg-warning' }}">
                                {{ $order->payment_status_label }}
                            </span>
                        </div>
                    </div>

                    {{-- Return Status Info --}}
                    @if($return = $order->returns->sortByDesc('created_at')->first())
                        <div class="card border-{{ $return->status === 'Từ chối' ? 'danger' : 'info' }} mt-3">
                            <div class="card-header bg-{{ $return->status === 'Từ chối' ? 'danger' : 'info' }} text-white">
                                <i class="fa fa-undo"></i> Yêu cầu hoàn trả: <strong>{{ $return->status }}</strong>
                            </div>
                            <div class="card-body">
                                <div class="row mb-2">
                                    <div class="col-6">
                                        <small class="text-muted">Mã:</small><br>
                                        <strong>{{ $return->return_code }}</strong>
                                    </div>
                                    <div class="col-6">
                                        <small class="text-muted">Vận chuyển:</small><br>
                                        <strong>{{ $return->shipping_status }}</strong>
                                    </div>
                                </div>
                                
                                @if($return->status === 'Từ chối' && $return->admin_note)
                                    <div class="alert alert-danger mt-2">
                                        <strong>Lý do từ chối:</strong><br>
                                        {{ $return->admin_note }}
                                    </div>
                                @endif
                                
                                @if($return->status === 'Đã hoàn tiền')
                                    <div class="alert alert-success mt-2">
                                        <i class="fa fa-check-circle"></i> Đã hoàn tiền vào: {{ $return->refunded_at ? $return->refunded_at->format('d/m/Y H:i') : '' }}
                                    </div>
                                @endif

                                {{-- Evidence Images --}}
                                @if($return->images->where('type', 'evidence')->count() > 0)
                                    <div class="mt-3 pb-3 border-bottom">
                                        <h6 class="text-primary mb-2"><i class="fa fa-image"></i> Ảnh minh chứng:</h6>
                                        <div class="row g-2">
                                            @foreach($return->images->where('type', 'evidence') as $image)
                                                <div class="col-6 col-md-3">
                                                    <div class="image-thumbnail-wrapper" onclick="showImageModal('{{ asset('storage/' . $image->path) }}')">
                                                        <img src="{{ asset('storage/' . $image->path) }}" 
                                                             class="w-100 rounded shadow-sm" 
                                                             style="height: 100px; object-fit: cover; cursor: pointer; transition: transform 0.2s;"
                                                             onmouseover="this.style.transform='scale(1.05)'" 
                                                             onmouseout="this.style.transform='scale(1)'"
                                                             alt="Ảnh minh chứng">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Refund Proof Images --}}
                                @if($return->status === 'Đã hoàn tiền' && $return->images->where('type', 'refund_proof')->count() > 0)
                                    <div class="mt-3">
                                        <h6 class="text-success mb-2"><i class="fa fa-money-bill-wave"></i> Ảnh minh chứng hoàn tiền:</h6>
                                        <div class="row g-2">
                                            @foreach($return->images->where('type', 'refund_proof') as $image)
                                                <div class="col-6 col-md-3">
                                                    <div class="image-thumbnail-wrapper" onclick="showImageModal('{{ asset('storage/' . $image->path) }}')">
                                                        <img src="{{ asset('storage/' . $image->path) }}" 
                                                             class="w-100 rounded shadow-sm border border-success" 
                                                             style="height: 100px; object-fit: cover; cursor: pointer; transition: transform 0.2s;"
                                                             onmouseover="this.style.transform='scale(1.05)'" 
                                                             onmouseout="this.style.transform='scale(1)'"
                                                             alt="Ảnh minh chứng hoàn tiền">
                                                    </div>
                                                </div>
                                            @endforeach
                                        </div>
                                    </div>
                                @endif

                                {{-- Mark as Shipped Button --}}
                                @if($return->canMarkAsShipped())
                                    <form action="{{ route('client.returns.mark-shipped', $return) }}" method="POST" class="mt-3">
                                        @csrf
                                        <button type="submit" class="btn btn-primary" onclick="return confirm('Xác nhận bạn đã gửi hàng hoàn trả?')">
                                            <i class="fa fa-truck"></i> Xác nhận đã gửi hàng
                                        </button>
                                    </form>
                                @endif
                            </div>
                        </div>
                    @endif

                    <!-- Shipping Information -->
                    <div class="section-title mt-4">
                        <h4 class="title">Thông tin giao hàng</h4>
                    </div>
                    <ul class="list-unstyled" style="line-height: 1.8;">
                        <li><strong>Tên người nhận:</strong> {{ $order->shipping_full_name }}</li>
                        <li><strong>Số điện thoại:</strong> {{ $order->shipping_phone }}</li>
                        @if($order->shipping_email)
                            <li><strong>Email:</strong> {{ $order->shipping_email }}</li>
                        @endif
                        <li><strong>Địa chỉ:</strong> {{ $order->shipping_address }}</li>
                        @if($order->shipping_city || $order->shipping_district || $order->shipping_ward)
                            <li>
                                <strong>Khu vực:</strong>
                                {{ collect([$order->shipping_ward, $order->shipping_district, $order->shipping_city])->filter()->implode(', ') }}
                            </li>
                        @endif
                        <li><strong>Phương thức thanh toán:</strong> {{ $paymentMethods[$order->payment_method] ?? strtoupper($order->payment_method) }}</li>
                        @if($order->notes)
                            <li><strong>Ghi chú:</strong> {{ $order->notes }}</li>
                        @endif
                    </ul>
                </div>
            </div>

                    <div class="col-md-4">
                <!-- Order Summary -->
                <div class="order-summary">
                    <div class="section-title">
                        <h4 class="title">Tóm tắt đơn hàng</h4>
                    </div>

                    <div class="order-products">
                        @foreach ($order->items as $item)
                            <div class="order-col mb-2 pb-2" style="border-bottom: 1px solid #eee;">
                                <div>
                                    <strong>{{ $item->quantity }} x {{ $item->product_name }}</strong>
                                    @if($item->product_image)
                                        <br>
                                        <img src="{{ asset($item->product_image) }}" 
                                             alt="{{ $item->product_name }}" 
                                             style="max-width: 60px; max-height: 60px; margin-top: 5px;">
                                    @endif
                                </div>
                                <div>
                                    <strong>{{ number_format($item->total_price, 0, ',', '.') }} ₫</strong>
                                    <br>
                                    <small class="text-muted">{{ number_format($item->unit_price, 0, ',', '.') }} ₫/sản phẩm</small>
                                </div>
                            </div>
                        @endforeach
                    </div>

                    <div class="order-col">
                        <div>Tạm tính</div>
                        <div>{{ number_format($order->calculateSubtotalFromItems(), 0, ',', '.') }} ₫</div>
                    </div>
                    <div class="order-col">
                        <div>Phí vận chuyển</div>
                        <div>{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</div>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="order-col">
                            <div>Giảm giá</div>
                            <div class="text-success">-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</div>
                        </div>
                        @if($order->coupon)
                            <div class="order-col">
                                <div>
                                    <small class="text-muted">
                                        <i class="fa fa-tag"></i> Mã: {{ $order->coupon->code }}
                                    </small>
                                </div>
                            </div>
                        @endif
                    @endif
                    <div class="order-col">
                        <div><strong>Tổng cộng</strong></div>
                        <div><strong class="order-total">{{ number_format(max($order->calculateSubtotalFromItems() - $order->discount_amount + $order->shipping_fee, 0), 0, ',', '.') }} ₫</strong></div>
                    </div>
                </div>
            </div>
        </div>

                <div class="text-center mt-4">
                    <a href="{{ route('client.orders.index') }}" class="btn btn-secondary">
                        <i class="fa fa-arrow-left"></i> Quay lại danh sách đơn hàng
                    </a>
                    <a href="{{ route('client.index') }}" class="btn btn-primary">
                        <i class="fa fa-home"></i> Về trang chủ
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Image Modal --}}
<div class="modal fade" id="imageModal" tabindex="-1" role="dialog">
    <div class="modal-dialog modal-lg modal-dialog-centered" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Xem ảnh</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body text-center p-0">
                <img id="modalImage" src="" class="img-fluid w-100" alt="Ảnh" style="max-height: 80vh; object-fit: contain;">
            </div>
        </div>
    </div>
</div>

<script>
function showImageModal(imageUrl) {
    document.getElementById('modalImage').src = imageUrl;
    // Try jQuery first, fallback to vanilla JS
    if (typeof jQuery !== 'undefined') {
        jQuery('#imageModal').modal('show');
    } else if (typeof bootstrap !== 'undefined') {
        var modal = new bootstrap.Modal(document.getElementById('imageModal'));
        modal.show();
    } else {
        // Fallback: open in new tab if no modal library
        window.open(imageUrl, '_blank');
    }
}
</script>

@endsection
