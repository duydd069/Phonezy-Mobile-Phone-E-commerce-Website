@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">
            <i class="fa fa-undo"></i> Chi tiết yêu cầu hoàn trả: {{ $return->return_code }}
        </h3>
        <a href="{{ route('admin.returns.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Quay lại
        </a>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    <div class="row">
        {{-- Left Column --}}
        <div class="col-md-8">
            {{-- Return Information --}}
            <div class="card mb-3">
                <div class="card-header bg-primary text-white">
                    <strong>Thông tin yêu cầu hoàn trả</strong>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-md-6">
                            <p><strong>Mã hoàn trả:</strong> {{ $return->return_code }}</p>
                            <p><strong>Ngày tạo:</strong> {{ $return->created_at->format('d/m/Y H:i') }}</p>
                            <p><strong>Trạng thái:</strong> 
                                <span class="badge {{ $return->status_badge_class }}">{{ $return->status_label }}</span>
                            </p>
                        </div>
                        <div class="col-md-6">
                            <p><strong>Trạng thái vận chuyển:</strong> {{ $return->shipping_status_label }}</p>
                            @if($return->returned_at)
                                <p><strong>Ngày gửi hàng:</strong> {{ $return->returned_at->format('d/m/Y H:i') }}</p>
                            @endif
                            @if($return->received_at)
                                <p><strong>Ngày nhận hàng:</strong> {{ $return->received_at->format('d/m/Y H:i') }}</p>
                            @endif
                            @if($return->refunded_at)
                                <p><strong>Ngày hoàn tiền:</strong> {{ $return->refunded_at->format('d/m/Y H:i') }}</p>
                            @endif
                        </div>
                    </div>
                </div>
            </div>

            {{-- Order Information --}}
            <div class="card mb-3">
                <div class="card-header bg-info text-white">
                    <strong>Thông tin đơn hàng</strong>
                </div>
                <div class="card-body">
                    <p><strong>Mã đơn:</strong> 
                        <a href="{{ route('admin.orders.show', $return->order) }}" target="_blank">
                            #{{ str_pad($return->order_id, 6, '0', STR_PAD_LEFT) }}
                        </a>
                    </p>
                    <p><strong>Khách hàng:</strong> {{ $return->order->shipping_full_name }}</p>
                    <p><strong>Email:</strong> {{ $return->order->shipping_email }}</p>
                    <p><strong>Tổng tiền:</strong> 
                        <span class="text-danger fw-bold">{{ number_format($return->order->total, 0, ',', '.') }}₫</span>
                    </p>
                    
                    <h6 class="mt-3">Sản phẩm:</h6>
                    <ul>
                        @foreach($return->order->items as $item)
                            <li>{{ $item->product_name }} x {{ $item->quantity }}</li>
                        @endforeach
                    </ul>
                </div>
            </div>

            {{-- Customer Information --}}
            <div class="card mb-3">
                <div class="card-header bg-warning">
                    <strong>Thông tin hoàn tiền</strong>
                </div>
                <div class="card-body">
                    <p><strong>Số điện thoại liên hệ:</strong> {{ $return->contact_phone }}</p>
                    <p><strong>Phương thức hoàn tiền:</strong> 
                        <span class="badge bg-info">{{ $return->refund_method }}</span>
                    </p>
                    
                    @if($return->refund_method === 'Ngân hàng')
                        <hr>
                        <p><strong>Ngân hàng:</strong> {{ $return->bank_name }}</p>
                        <p><strong>Số tài khoản:</strong> {{ $return->bank_account_number }}</p>
                        <p><strong>Chủ tài khoản:</strong> {{ $return->bank_account_name }}</p>
                    @endif
                </div>
            </div>

            {{-- Reason --}}
            <div class="card mb-3">
                <div class="card-header bg-secondary text-white">
                    <strong>Lý do hoàn trả</strong>
                </div>
                <div class="card-body">
                    <p>{{ $return->reason }}</p>
                </div>
            </div>

            {{-- Evidence Images --}}
            <div class="card mb-3">
                <div class="card-header bg-dark text-white">
                    <strong>Ảnh minh chứng</strong>
                </div>
                <div class="card-body">
                    <div class="row g-2">
                        @forelse($return->images->where('type', 'evidence') as $image)
                            <div class="col-md-3 col-6">
                                <a href="{{ asset('storage/' . $image->path) }}" target="_blank">
                                    <img src="{{ asset('storage/' . $image->path) }}" 
                                         class="img-thumbnail" 
                                         style="width: 100%; height: 150px; object-fit: cover; cursor: pointer;">
                                </a>
                            </div>
                        @empty
                            <p class="text-muted">Không có ảnh</p>
                        @endforelse
                    </div>
                </div>
            </div>

            {{-- Refund Proof Images (if exists) --}}
            @if($return->images->where('type', 'refund_proof')->count() > 0)
                <div class="card mb-3">
                    <div class="card-header bg-success text-white">
                        <strong>Ảnh minh chứng hoàn tiền</strong>
                    </div>
                    <div class="card-body">
                        <div class="row g-2">
                            @foreach($return->images->where('type', 'refund_proof') as $image)
                                <div class="col-md-3 col-6">
                                    <a href="{{ asset('storage/' . $image->path) }}" target="_blank">
                                        <img src="{{ asset('storage/' . $image->path) }}" 
                                             class="img-thumbnail" 
                                             style="width: 100%; height: 150px; object-fit: cover; cursor: pointer;">
                                    </a>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            @endif

            {{-- Admin Note (if rejected) --}}
            @if($return->admin_note)
                <div class="card mb-3">
                    <div class="card-header bg-danger text-white">
                        <strong>Phản hồi từ chối</strong>
                    </div>
                    <div class="card-body">
                        <p>{{ $return->admin_note }}</p>
                    </div>
                </div>
            @endif
        </div>

        {{-- Right Column - Actions --}}
        <div class="col-md-4">
            <div class="card sticky-top" style="top: 20px;">
                <div class="card-header bg-primary text-white">
                    <strong>Thao tác</strong>
                </div>
                <div class="card-body">
                    @if($return->canApprove())
                        {{-- Pending - Show Approve/Reject --}}
                        <form action="{{ route('admin.returns.approve', $return) }}" method="POST" class="mb-2">
                            @csrf
                            <button type="submit" class="btn btn-success w-100" onclick="return confirm('Xác nhận phê duyệt yêu cầu hoàn trả?')">
                                <i class="fa fa-check"></i> Phê duyệt
                            </button>
                        </form>
                        
                        <button type="button" class="btn btn-danger w-100" data-bs-toggle="modal" data-bs-target="#rejectModal">
                            <i class="fa fa-times"></i> Từ chối
                        </button>

                    @elseif($return->status === 'Thông qua')
                        {{-- Approved - Waiting for customer to ship --}}
                        <div class="alert alert-info">
                            <i class="fa fa-info-circle"></i> Đang chờ khách hàng gửi hàng
                        </div>

                    @elseif($return->canConfirmReceived())
                        {{-- Shipping - Show Confirm Received --}}
                        <form action="{{ route('admin.returns.confirm-received', $return) }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-primary w-100" onclick="return confirm('Xác nhận đã nhận được hàng hoàn trả?')">
                                <i class="fa fa-box"></i> Xác nhận đã nhận hàng
                            </button>
                        </form>

                    @elseif($return->canProcessRefund())
                        {{-- Received - Show Process Refund --}}
                        <button type="button" class="btn btn-success w-100" data-bs-toggle="modal" data-bs-target="#refundModal" onclick="event.preventDefault();">
                            <i class="fa fa-money-bill"></i> Xử lý hoàn tiền
                        </button>

                    @elseif($return->status === 'Đã hoàn tiền')
                        {{-- Refunded - Complete --}}
                        <div class="alert alert-success">
                            <i class="fa fa-check-circle"></i> Đã hoàn tiền thành công
                            @if($return->refundedBy)
                                <br><small>Bởi: {{ $return->refundedBy->name }}</small>
                            @endif
                        </div>

                    @elseif($return->status === 'Từ chối')
                        {{-- Rejected --}}
                        <div class="alert alert-danger">
                            <i class="fa fa-times-circle"></i> Đã từ chối yêu cầu
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Reject Modal --}}
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.returns.reject', $return) }}" method="POST">
                @csrf
                <div class="modal-header bg-danger text-white">
                    <h5 class="modal-title">Từ chối yêu cầu hoàn trả</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="admin_note" class="form-label">Lý do từ chối <span class="text-danger">*</span></label>
                        <textarea class="form-control" id="admin_note" name="admin_note" rows="4" required 
                                  placeholder="Nhập lý do từ chối (tối thiểu 10 ký tự)"></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-danger">Từ chối yêu cầu</button>
                </div>
            </form>
        </div>
    </div>
</div>

{{-- Refund Modal --}}
<div class="modal fade" id="refundModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form action="{{ route('admin.returns.process-refund', $return) }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-header bg-success text-white">
                    <h5 class="modal-title">Xử lý hoàn tiền</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="border border-warning bg-light p-3 rounded mb-3" style="position: static;">
                        <strong class="text-warning"><i class="fa fa-info-circle"></i> Thông tin hoàn tiền:</strong><br>
                        <div class="mt-2">
                            <strong>Phương thức:</strong> {{ $return->refund_method }}<br>
                            @if($return->refund_method === 'Ngân hàng')
                                <strong>Ngân hàng:</strong> {{ $return->bank_name }}<br>
                                <strong>Số TK:</strong> {{ $return->bank_account_number }}<br>
                                <strong>Chủ TK:</strong> {{ $return->bank_account_name }}<br>
                            @else
                                <strong>SĐT Momo:</strong> {{ $return->contact_phone }}
                            @endif
                            <strong>Số tiền:</strong> <strong class="text-danger">{{ number_format($return->order->total, 0, ',', '.') }}₫</strong>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="refund_images" class="form-label">
                            Ảnh minh chứng hoàn tiền <span class="text-danger">*</span>
                        </label>
                        <input type="file" class="form-control" id="refund_images" name="refund_images[]" 
                               accept="image/*" multiple required>
                        <small class="text-muted">Tải lên ảnh giao dịch/chuyển khoản</small>
                    </div>


                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Hủy</button>
                    <button type="submit" class="btn btn-success">Xác nhận hoàn tiền</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
