@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">
            <i class="fa fa-file-text-o"></i> Chi Tiết Đơn Hàng #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
        </h3>
        <div class="d-flex gap-2">
            <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                <i class="fa fa-arrow-left"></i> Quay lại
            </a>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        {{-- Left Column: Order Information --}}
        <div class="col-md-8">
            {{-- Customer Information --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="m-0">Thông Tin Khách Hàng</h5>
                </div>
                <div class="card-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <strong>Họ và tên:</strong>
                            <div>{{ $order->shipping_full_name }}</div>
                        </div>
                        <div class="col-md-6">
                            <strong>Số điện thoại:</strong>
                            <div>{{ $order->shipping_phone }}</div>
                        </div>
                        @if($order->shipping_email)
                            <div class="col-md-6">
                                <strong>Email:</strong>
                                <div>{{ $order->shipping_email }}</div>
                            </div>
                        @endif
                        @if($order->user)
                            <div class="col-md-6">
                                <strong>Tài khoản:</strong>
                                <div>
                                    {{ $order->user->name }} (ID: {{ $order->user_id }})
                                </div>
                            </div>
                        @endif
                    </div>
                </div>
            </div>

            {{-- Shipping Address --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="m-0">Địa Chỉ Giao Hàng</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Địa chỉ:</strong> {{ $order->shipping_address }}
                    </p>
                    @if($order->shipping_ward || $order->shipping_district || $order->shipping_city)
                        <p>
                            <strong>Khu vực:</strong>
                            {{ collect([$order->shipping_ward, $order->shipping_district, $order->shipping_city])->filter()->implode(', ') }}
                        </p>
                    @endif
                    @if($order->notes)
                        <p>
                            <strong>Ghi chú:</strong> {{ $order->notes }}
                        </p>
                    @endif
                </div>
            </div>

            {{-- Order Items --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="m-0">Sản Phẩm Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>Sản phẩm</th>
                                    <th class="text-end">Đơn giá</th>
                                    <th class="text-center">Số lượng</th>
                                    <th class="text-end">Thành tiền</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($order->items as $item)
                                    <tr>
                                        <td>
                                            <div class="d-flex align-items-center">
                                                @if($item->product_image)
                                                    <img src="{{ $item->product_image }}" 
                                                         alt="{{ $item->product_name }}"
                                                         class="rounded me-2"
                                                         style="width: 60px; height: 60px; object-fit: cover; border: 1px solid #dee2e6;">
                                                @endif
                                                <div>
                                                    <strong>{{ $item->product_name }}</strong>
                                                    @if($item->product_id)
                                                        <br><small class="text-muted">Mã SP: {{ $item->product_id }}</small>
                                                    @endif
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-end">{{ number_format($item->unit_price, 0, ',', '.') }} ₫</td>
                                        <td class="text-center">{{ $item->quantity }}</td>
                                        <td class="text-end">
                                            <strong>{{ number_format($item->total_price, 0, ',', '.') }} ₫</strong>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Right Column: Status and Payment Information --}}
        <div class="col-md-4">
            {{-- Order Status --}}
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <h5 class="m-0">Trạng Thái Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <strong>Trạng thái hiện tại:</strong>
                        <div>
                            <span class="badge {{ $order->status_badge_class }}" style="font-size: 14px; padding: 8px 12px;">
                                {{ $order->status_label }}
                            </span>
                        </div>
                        @if($order->requiresPaymentBeforeConfirmation())
                            <div class="alert alert-warning mt-2 mb-0" style="font-size: 12px; padding: 8px;">
                                <i class="fa fa-exclamation-triangle"></i> 
                                <strong>Lưu ý:</strong> Đơn hàng thanh toán qua {{ strtoupper($order->payment_method) }} chưa được thanh toán. 
                                @if($order->payment_method === 'vnpay')
                                    <br><small>Bạn có thể xác nhận thanh toán thủ công ở phần "Thông Tin Thanh Toán" bên dưới (Demo mode).</small>
                                @else
                                    Chỉ có thể xác nhận sau khi khách hàng thanh toán thành công.
                                @endif
                            </div>
                        @endif
                    </div>

                    <form method="POST" action="{{ route('admin.orders.update-status', $order) }}">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="status" class="form-label">Thay đổi trạng thái:</label>
                            <select name="status" id="status" class="form-select" required>
                                <option value="{{ $order->status }}" selected>
                                    {{ $order->status_label }} (Hiện tại)
                                </option>
                                @php
                                    $validNextStatuses = $order->getValidNextStatuses();
                                    $allStatuses = \App\Models\Order::getAvailableStatuses();
                                @endphp
                                @if(count($validNextStatuses) > 0)
                                    @foreach($validNextStatuses as $statusKey)
                                        <option value="{{ $statusKey }}">
                                            {{ $allStatuses[$statusKey] ?? $statusKey }}
                                        </option>
                                    @endforeach
                                @else
                                    <option disabled>Không thể thay đổi trạng thái từ đây</option>
                                @endif
                            </select>
                            @if(count($validNextStatuses) > 0)
                                <small class="text-muted d-block mt-1">
                                    <i class="fa fa-info-circle"></i> Có thể chuyển sang: 
                                    {{ implode(', ', array_map(function($key) use ($allStatuses) {
                                        return $allStatuses[$key] ?? $key;
                                    }, $validNextStatuses)) }}
                                </small>
                            @else
                                <small class="text-danger d-block mt-1">
                                    <i class="fa fa-exclamation-triangle"></i> Đơn hàng này không thể thay đổi trạng thái
                                </small>
                            @endif
                        </div>
                        <button type="submit" class="btn btn-primary w-100" 
                                {{ count($validNextStatuses) === 0 ? 'disabled' : '' }}
                                onclick="return confirm('Bạn có chắc chắn muốn thay đổi trạng thái đơn hàng?');">
                            Cập nhật trạng thái
                        </button>
                    </form>
                </div>
            </div>

            {{-- Payment Information --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="m-0">Thông Tin Thanh Toán</h5>
                </div>
                <div class="card-body">
                    <p>
                        <strong>Phương thức:</strong><br>
                        {{ $paymentMethods[$order->payment_method] ?? strtoupper($order->payment_method) }}
                    </p>
                    <p>
                        <strong>Trạng thái:</strong><br>
                        <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                            {{ $order->payment_status_label }}
                        </span>
                    </p>
                    @if($order->paid_at)
                        <p>
                            <strong>Thời gian thanh toán:</strong><br>
                            {{ $order->paid_at->format('d/m/Y H:i') }}
                        </p>
                    @endif
                    @if($order->transaction_no)
                        <p>
                            <strong>Mã giao dịch VNPAY:</strong><br>
                            <code>{{ $order->transaction_no }}</code>
                        </p>
                    @elseif($order->payment_method === 'vnpay' && $order->payment_status === 'paid')
                        <p>
                            <strong>Mã giao dịch VNPAY:</strong><br>
                            <span class="text-muted">Chưa có</span>
                            <div class="mt-2">
                                <form method="POST" action="{{ route('admin.orders.query-transaction', $order) }}" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-info" title="Tra cứu từ VNPAY API">
                                        <i class="fa fa-search"></i> Tra cứu từ VNPAY
                                    </button>
                                </form>
                                <form method="POST" action="{{ route('admin.orders.generate-test-transaction', $order) }}" class="d-inline ms-2">
                                    @csrf
                                    <button type="submit" class="btn btn-sm btn-warning" 
                                            title="Tạo mã giao dịch test cho sandbox (dùng khi quét QR nhưng chưa chuyển khoản)"
                                            onclick="return confirm('Tạo mã giao dịch test cho sandbox? Mã này có thể dùng để test hoàn tiền.');">
                                        <i class="fa fa-plus-circle"></i> Tạo mã test
                                    </button>
                                </form>
                            </div>
                            <small class="text-muted d-block mt-1">
                                💡 <strong>Lưu ý:</strong> Nếu đã quét QR nhưng chưa chuyển khoản, hãy dùng "Tạo mã test" để có mã giao dịch test cho sandbox.
                            </small>
                        </p>
                    @endif
                    
                    {{-- Nút xác nhận thanh toán thủ công (Demo mode) --}}
                    @if($order->payment_method === 'vnpay' && $order->payment_status === 'pending')
                        <hr>
                        <div class="alert alert-info" style="font-size: 12px; padding: 10px; margin-bottom: 10px;">
                            <i class="fa fa-info-circle"></i> 
                            <strong>Demo Mode:</strong> VNPay chưa được kích hoạt. Bạn có thể xác nhận thanh toán thủ công để test flow.
                        </div>
                        <form method="POST" action="{{ route('admin.orders.confirm-payment', $order) }}" class="mt-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" 
                                    onclick="return confirm('Bạn có chắc chắn muốn xác nhận thanh toán cho đơn hàng này? (Demo mode)');">
                                <i class="fa fa-check-circle"></i> Xác nhận thanh toán (Demo)
                            </button>
                        </form>
                    @endif

                    {{-- Form hoàn tiền VNPAY --}}
                    @if($order->payment_method === 'vnpay' && $order->payment_status === 'paid')
                        <hr>
                        <div class="alert alert-warning" style="font-size: 12px; padding: 10px; margin-bottom: 10px;">
                            <i class="fa fa-info-circle"></i> 
                            <strong>Hoàn tiền VNPAY:</strong> Chức năng hoàn tiền qua VNPAY Sandbox.
                            <br><small><strong>Lưu ý:</strong> Mã giao dịch VNPAY (vnp_TransactionNo) KHÁC với mã đơn hàng (#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }})</small>
                            @if($order->transaction_no)
                                <br><small><strong>Mã giao dịch VNPAY:</strong> <code>{{ $order->transaction_no }}</code></small>
                                <br><small><strong>Mã đơn hàng:</strong> #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</small>
                            @else
                                <br><small class="text-danger">⚠️ Chưa có mã giao dịch VNPAY.</small>
                                <br><small class="text-info">💡 Bạn có thể: 
                                    <a href="#" onclick="document.getElementById('queryForm').submit(); return false;" class="text-decoration-underline">Tra cứu từ VNPAY</a>, 
                                    <a href="#" onclick="document.getElementById('generateTestForm').submit(); return false;" class="text-decoration-underline">Tạo mã test</a> 
                                    hoặc nhập thủ công bên dưới
                                </small>
                                <form method="POST" action="{{ route('admin.orders.query-transaction', $order) }}" id="queryForm" class="d-none">
                                    @csrf
                                </form>
                                <form method="POST" action="{{ route('admin.orders.generate-test-transaction', $order) }}" id="generateTestForm" class="d-none">
                                    @csrf
                                </form>
                            @endif
                        </div>
                        <form method="POST" action="{{ route('admin.orders.refund', $order) }}" class="mt-2" id="refundForm">
                            @csrf
                            <div class="mb-2">
                                <label for="refund_amount" class="form-label" style="font-size: 12px;">Số tiền hoàn (₫):</label>
                                <input type="number" 
                                       class="form-control form-control-sm" 
                                       id="refund_amount" 
                                       name="amount" 
                                       value="{{ $order->total }}" 
                                       min="0" 
                                       max="{{ $order->total }}"
                                       step="1000">
                                <small class="text-muted" style="font-size: 11px;">
                                    Để trống để hoàn toàn bộ ({{ number_format($order->total, 0, ',', '.') }} ₫)
                                </small>
                            </div>
                            @if(!$order->transaction_no)
                                <div class="mb-2">
                                    <label for="transaction_no" class="form-label" style="font-size: 12px;">
                                        Mã giao dịch VNPAY (vnp_TransactionNo) <span class="text-danger">*</span>:
                                    </label>
                                    <input type="text" 
                                           class="form-control form-control-sm" 
                                           id="transaction_no" 
                                           name="transaction_no" 
                                           placeholder="Nhập mã giao dịch từ VNPAY (KHÔNG phải mã đơn hàng)"
                                           required>
                                    <small class="text-muted" style="font-size: 10px;">
                                        ⚠️ Đây là mã giao dịch do VNPAY tạo, KHÔNG phải mã đơn hàng (#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }})
                                    </small>
                                </div>
                                <div class="mb-2">
                                    <label for="transaction_date" class="form-label" style="font-size: 12px;">Ngày giao dịch (YYYYMMDDHHmmss):</label>
                                    <input type="text" 
                                           class="form-control form-control-sm" 
                                           id="transaction_date" 
                                           name="transaction_date" 
                                           placeholder="VD: 20241217162535"
                                           maxlength="14">
                                </div>
                            @endif
                            <button type="submit" 
                                    class="btn btn-warning w-100" 
                                    onclick="return confirm('Bạn có chắc chắn muốn hoàn tiền cho đơn hàng này? Hành động này không thể hoàn tác.');">
                                <i class="fa fa-undo"></i> Hoàn tiền qua VNPAY
                            </button>
                        </form>
                    @endif
                </div>
            </div>

            {{-- Order Summary --}}
            <div class="card mb-3">
                <div class="card-header">
                    <h5 class="m-0">Tóm Tắt Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <div class="d-flex justify-content-between mb-2">
                        <span>Tạm tính:</span>
                        <strong>{{ number_format($order->subtotal, 0, ',', '.') }} ₫</strong>
                    </div>
                    <div class="d-flex justify-content-between mb-2">
                        <span>Phí vận chuyển:</span>
                        <strong>{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</strong>
                    </div>
                    @if($order->discount_amount > 0)
                        <div class="d-flex justify-content-between mb-2 text-success">
                            <span>Giảm giá:</span>
                            <strong>-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</strong>
                        </div>
                    @endif
                    <hr>
                    <div class="d-flex justify-content-between">
                        <strong>Tổng cộng:</strong>
                        <strong style="color: #F7941D; font-size: 18px;">
                            {{ number_format($order->total, 0, ',', '.') }} ₫
                        </strong>
                    </div>
                </div>
            </div>

            {{-- Order Information --}}
            <div class="card">
                <div class="card-header">
                    <h5 class="m-0">Thông Tin Đơn Hàng</h5>
                </div>
                <div class="card-body">
                    <p><strong>Ngày đặt hàng:</strong><br>{{ $order->created_at->format('d/m/Y H:i') }}</p>
                    @if($order->updated_at != $order->created_at)
                        <p><strong>Cập nhật lần cuối:</strong><br>{{ $order->updated_at->format('d/m/Y H:i') }}</p>
                    @endif
                    <p><strong>Mã đơn hàng:</strong><br>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection