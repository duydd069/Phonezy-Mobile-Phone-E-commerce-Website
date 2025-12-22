@extends('electro.layout')

@section('content')
<style>
    /* Minimalist Order Detail Styles */
    :root {
        --subtle-gray: #fcfcfc;
        --border-color: #eee;
        --text-main: #1a1a1a;
        --text-muted: #757575;
        --primary-accent: #000; /* Minimalist thường dùng đen làm điểm nhấn */
    }

    .order-detail-wrapper {
        background-color: #fff;
        color: var(--text-main);
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, sans-serif;
    }

    .minimal-card {
        border: 1px solid var(--border-color);
        border-radius: 0; /* Minimalist thường thích góc vuông hoặc bo rất nhẹ */
        box-shadow: none !important;
        margin-bottom: 2rem;
    }

    .minimal-card-header {
        background: transparent;
        border-bottom: 1px solid var(--border-color);
        padding: 1.25rem 0;
        margin: 0 1.5rem;
        text-transform: uppercase;
        letter-spacing: 1px;
        font-size: 0.85rem;
        font-weight: 700;
    }

    .badge-minimal {
        border-radius: 0;
        font-weight: 500;
        padding: 0.5em 1em;
        text-transform: uppercase;
        font-size: 0.7rem;
    }

    .table-minimal thead th {
        background-color: var(--subtle-gray);
        border-bottom: 1px solid var(--border-color);
        text-transform: uppercase;
        font-size: 0.75rem;
        letter-spacing: 0.5px;
        font-weight: 600;
        padding: 1rem 1.5rem;
    }

    .table-minimal td {
        padding: 1.5rem;
        vertical-align: middle;
        border-top: 1px solid var(--border-color);
    }

    .summary-line {
        display: flex;
        justify-content: space-between;
        margin-bottom: 0.75rem;
        font-size: 0.95rem;
    }

    .total-line {
        border-top: 2px solid #000;
        margin-top: 1rem;
        padding-top: 1rem;
        font-size: 1.25rem;
        font-weight: 700;
    }

    .btn-minimal {
        border-radius: 0;
        text-transform: uppercase;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 1px;
        padding: 0.75rem 1.5rem;
        transition: all 0.2s ease;
    }

    .btn-minimal-dark {
        background: #000;
        color: #fff;
        border: 1px solid #000;
    }

    .btn-minimal-dark:hover {
        background: #333;
        color: #fff;
    }

    .btn-minimal-outline {
        border: 1px solid #ddd;
        color: #555;
    }

    .btn-minimal-outline:hover {
        background: #f8f8f8;
        border-color: #000;
        color: #000;
    }

    .shipping-info-block p {
        margin-bottom: 0.25rem;
        font-size: 0.95rem;
        color: var(--text-muted);
    }
</style>

<div class="order-detail-wrapper py-5">
    <div class="container">
        {{-- Top Navigation --}}
        <div class="d-flex justify-content-between align-items-baseline mb-5">
            <div>
                <h2 class="fw-bold mb-1">ĐƠN HÀNG #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h2>
                <p class="text-muted small">Đặt lúc: {{ $order->created_at->format('H:i, d F Y') }}</p>
            </div>
            <a href="{{ route('client.orders.index') }}" class="btn btn-minimal btn-minimal-outline">
                <i class="fa fa-chevron-left me-2"></i> Trở về danh sách
            </a>
        </div>

        {{-- Alerts --}}
        @if(session('success'))
            <div class="alert alert-dark border-0 rounded-0 small mb-4">
                {{ session('success') }}
            </div>
        @endif

        <div class="row">
            {{-- Main Content --}}
            <div class="col-lg-8">
                
                {{-- Status & Returns Tracking --}}
                <div class="minimal-card p-4 mb-4">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <span class="text-muted small text-uppercase d-block mb-1">Trạng thái hiện tại</span>
                            <span class="badge badge-minimal {{ $order->status_badge_class }}">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        
                        @php
                            $rejectedReturn = $order->returns->where('status', 'Từ chối')->first();
                            $hasActiveReturn = $order->returns->whereIn('status', ['Chưa giải quyết', 'Thông qua', 'Đang vận chuyển', 'Đã nhận', 'Đã hoàn tiền'])->count() > 0;
                        @endphp

                        @if(!$rejectedReturn && !$hasActiveReturn && $order->canBeReturned())
                            <a href="{{ route('client.orders.return.create', $order) }}" class="btn btn-minimal btn-minimal-outline border-warning text-warning">
                                Yêu cầu hoàn hàng
                            </a>
                        @endif
                    </div>

                    {{-- Active Return Info --}}
                    @if($return = $order->returns->whereIn('status', ['Chưa giải quyết', 'Thông qua', 'Đang vận chuyển', 'Đã nhận', 'Đã hoàn tiền'])->sortByDesc('created_at')->first())
                        <div class="mt-4 pt-4 border-top">
                            <h6 class="fw-bold text-uppercase small mb-3">Thông tin hoàn trả</h6>
                            <div class="bg-light p-3 border">
                                <div class="row align-items-center">
                                    <div class="col-md-8">
                                        <p class="mb-1 small">Mã hoàn trả: <strong>{{ $return->return_code }}</strong></p>
                                        <p class="mb-0 small">Tình trạng: <span class="text-primary">{{ $return->status_label }}</span></p>
                                    </div>
                                    @if($return->canMarkAsShipped())
                                        <div class="col-md-4 text-end">
                                            <form action="{{ route('client.returns.mark-shipped', $return) }}" method="POST">
                                                @csrf
                                                <button type="submit" class="btn btn-sm btn-minimal btn-minimal-dark">Đã gửi hàng</button>
                                            </form>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    @endif
                </div>

                {{-- Product List --}}
                <div class="minimal-card">
                    <div class="minimal-card-header">Sản phẩm trong đơn hàng</div>
                    <div class="table-responsive">
                        <table class="table table-minimal mb-0">
                            <thead>
                                <tr>
                                    <th>Mô tả sản phẩm</th>
                                    <th class="text-center">SL</th>
                                    <th class="text-end">Giá</th>
                                    <th class="text-end">Tổng</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product_image)
                                                    <img src="{{ asset('storage/' . $item->product_image) }}" 
                                                         style="width: 50px; height: 50px; object-fit: cover; filter: grayscale(20%); border: 1px solid #eee;" class="me-3">
                                                @endif
                                                <span class="fw-bold small">{{ $item->product_name }}</span>
                                            </div>
                                        </td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end text-muted small">{{ number_format($item->unit_price, 0, ',', '.') }}₫</td>
                                        <td class="text-end fw-bold">{{ number_format($item->total_price, 0, ',', '.') }}₫</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>

            {{-- Sidebar Summary --}}
            <div class="col-lg-4">
                {{-- Payment Summary --}}
                <div class="minimal-card p-4">
                    <h6 class="fw-bold text-uppercase small mb-4">Chi phí đơn hàng</h6>
                    
                    <div class="summary-line">
                        <span class="text-muted">Tạm tính</span>
                        <span>{{ number_format($order->calculateSubtotalFromItems(), 0, ',', '.') }}₫</span>
                    </div>
                    <div class="summary-line">
                        <span class="text-muted">Phí vận chuyển</span>
                        <span>{{ number_format($order->shipping_fee, 0, ',', '.') }}₫</span>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="summary-line text-success">
                            <span>Giảm giá</span>
                            <span>-{{ number_format($order->discount_amount, 0, ',', '.') }}₫</span>
                        </div>
                    @endif
                    
                    <div class="total-line d-flex justify-content-between">
                        <span>TỔNG CỘNG</span>
                        <span>{{ number_format(max($order->calculateSubtotalFromItems() - $order->discount_amount + $order->shipping_fee, 0), 0, ',', '.') }}₫</span>
                    </div>

                    <div class="mt-4 pt-4 border-top">
                        <div class="summary-line">
                            <span class="text-muted small">Thanh toán qua</span>
                            <span class="small fw-bold">{{ strtoupper($order->payment_method) }}</span>
                        </div>
                        <div class="summary-line">
                            <span class="text-muted small">Trạng thái tiền</span>
                            <span class="small fw-bold {{ $order->payment_status == 1 ? 'text-success' : 'text-warning' }}">
                                {{ $order->payment_status == 1 ? 'Đã xong' : 'Chờ xử lý' }}
                            </span>
                        </div>
                    </div>
                </div>

                {{-- Delivery Info --}}
                <div class="minimal-card p-4">
                    <h6 class="fw-bold text-uppercase small mb-4">Thông tin giao nhận</h6>
                    <div class="shipping-info-block">
                        <p class="text-main fw-bold">{{ $order->shipping_full_name }}</p>
                        <p>{{ $order->shipping_phone }}</p>
                        @if($order->shipping_email)
                            <p>{{ $order->shipping_email }}</p>
                        @endif
                        <p class="mt-3 small lh-base">
                            {{ $order->shipping_address }}<br>
                            {{ implode(', ', array_filter([$order->shipping_ward, $order->shipping_district, $order->shipping_city])) }}
                        </p>
                    </div>

                    @if($order->notes)
                        <div class="mt-4 pt-3 border-top">
                            <span class="text-muted small text-uppercase d-block mb-1">Ghi chú từ khách hàng</span>
                            <p class="small fst-italic">"{{ $order->notes }}"</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection