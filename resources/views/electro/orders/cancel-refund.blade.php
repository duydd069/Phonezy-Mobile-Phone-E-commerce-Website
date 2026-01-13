@extends('electro.layout')

@section('title', 'Hủy đơn hàng & Hoàn tiền')

@section('content')
<style>
    .cancel-refund-page {
        font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, Helvetica, Arial, sans-serif;
        color: #1a1a1a;
        background-color: #fff;
    }
    .minimal-title {
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 1px;
        border-bottom: 2px solid #000;
        display: inline-block;
        padding-bottom: 10px;
    }
    .minimal-card {
        border: 1px solid #eaeaea;
        border-radius: 0;
        box-shadow: none !important;
        margin-bottom: 30px;
    }
    .minimal-card-header {
        background-color: #fafafa !important;
        border-bottom: 1px solid #eaeaea !important;
        color: #000 !important;
        text-transform: uppercase;
        font-size: 0.85rem;
        font-weight: 600;
        padding: 15px 20px !important;
    }
    .form-label {
        font-size: 0.8rem;
        text-transform: uppercase;
        font-weight: 700;
        letter-spacing: 0.5px;
        color: #555;
        margin-bottom: 8px;
    }
    .form-control, .form-select {
        border-radius: 0;
        border: 1px solid #ddd;
        padding: 12px;
        font-size: 0.9rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: #000;
        box-shadow: none;
    }
    .btn-minimal-dark {
        background-color: #000;
        color: #fff;
        border-radius: 0;
        padding: 12px 25px;
        text-transform: uppercase;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 1px;
        border: 1px solid #000;
        transition: all 0.3s ease;
    }
    .btn-minimal-dark:hover {
        background-color: #fff;
        color: #000;
    }
    .btn-minimal-outline {
        background-color: #fff;
        color: #666;
        border: 1px solid #ddd;
        border-radius: 0;
        padding: 12px 25px;
        text-transform: uppercase;
        font-size: 0.8rem;
        transition: all 0.3s ease;
    }
    .btn-minimal-outline:hover {
        border-color: #000;
        color: #000;
    }
    .alert {
        border-radius: 0;
        border-left: 4px solid;
    }
    .order-info-box {
        background-color: #f8f9fa;
        border: 1px solid #eaeaea;
        padding: 20px;
        margin-bottom: 30px;
    }
</style>

<div class="section cancel-refund-page">
    <div class="container">
        <div class="row">
            @include('electro.account._sidebar')
            
            <div class="col-md-9">
                <h2 class="minimal-title mb-4">Hủy đơn hàng & Hoàn tiền</h2>

                @if(session('error'))
                    <div class="alert alert-danger">
                        {{ session('error') }}
                    </div>
                @endif

                @if($errors->any())
                    <div class="alert alert-danger">
                        <ul class="mb-0">
                            @foreach($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <div class="row">
                    <div class="col-md-8">
                        <div class="order-info-box">
                            <h5 class="mb-3">Thông tin đơn hàng</h5>
                            <div class="row">
                                <div class="col-md-6 mb-2">
                                    <strong>Mã đơn hàng:</strong><br>
                                    <span>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</span>
                                </div>
                                <div class="col-md-6 mb-2">
                                    <strong>Tổng tiền:</strong><br>
                                    <span class="text-danger fw-bold">{{ number_format($order->total, 0, ',', '.') }} ₫</span>
                                </div>
                            </div>
                        </div>

                        <div class="minimal-card card">
                            <div class="minimal-card-header">Thông tin hoàn tiền</div>
                            <div class="card-body p-4">
                                <form action="{{ route('client.orders.cancel-refund', $order) }}" method="POST" id="cancelRefundForm">
                                    @csrf

                                    <div class="mb-4">
                                        <label for="contact_phone" class="form-label">Số điện thoại liên hệ *</label>
                                        <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                               id="contact_phone" name="contact_phone" 
                                               value="{{ old('contact_phone', $order->shipping_phone) }}" 
                                               required>
                                        @error('contact_phone')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="mb-4">
                                        <label for="bank_name" class="form-label">Tên ngân hàng *</label>
                                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror" 
                                               id="bank_name" name="bank_name" 
                                               value="{{ old('bank_name') }}" 
                                               placeholder="Ví dụ: Vietcombank, Techcombank, BIDV..."
                                               required>
                                        @error('bank_name')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="row mb-4">
                                        <div class="col-md-6">
                                            <label for="bank_account_number" class="form-label">Số tài khoản *</label>
                                            <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" 
                                                   id="bank_account_number" name="bank_account_number" 
                                                   value="{{ old('bank_account_number') }}" 
                                                   required>
                                            @error('bank_account_number')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                        <div class="col-md-6">
                                            <label for="bank_account_name" class="form-label">Tên chủ tài khoản *</label>
                                            <input type="text" class="form-control @error('bank_account_name') is-invalid @enderror" 
                                                   id="bank_account_name" name="bank_account_name" 
                                                   value="{{ old('bank_account_name') }}" 
                                                   required>
                                            @error('bank_account_name')
                                                <div class="invalid-feedback">{{ $message }}</div>
                                            @enderror
                                        </div>
                                    </div>

                                    <div class="mb-4">
                                        <label for="reason" class="form-label">Lý do hủy đơn *</label>
                                        <select class="form-select @error('reason') is-invalid @enderror" 
                                                id="reason" name="reason" required>
                                            <option value="">-- Chọn lý do hủy đơn --</option>
                                            <option value="Không muốn mua nữa" {{ old('reason') == 'Không muốn mua nữa' ? 'selected' : '' }}>Không muốn mua nữa</option>
                                            <option value="Phát hiện giá tốt hơn ở nơi khác" {{ old('reason') == 'Phát hiện giá tốt hơn ở nơi khác' ? 'selected' : '' }}>Phát hiện giá tốt hơn ở nơi khác</option>
                                            <option value="Đặt nhầm sản phẩm" {{ old('reason') == 'Đặt nhầm sản phẩm' ? 'selected' : '' }}>Đặt nhầm sản phẩm</option>
                                            <option value="Thay đổi kế hoạch" {{ old('reason') == 'Thay đổi kế hoạch' ? 'selected' : '' }}>Thay đổi kế hoạch</option>
                                            <option value="Khác" {{ old('reason') == 'Khác' ? 'selected' : '' }}>Khác</option>
                                        </select>
                                        @error('reason')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                    </div>

                                    <div class="alert alert-info">
                                        <strong><i class="fa fa-info-circle"></i> Lưu ý:</strong><br>
                                        Yêu cầu hủy đơn và hoàn tiền của bạn sẽ được xem xét bởi quản trị viên. 
                                        Sau khi được xác nhận, tiền sẽ được hoàn vào tài khoản ngân hàng bạn cung cấp.
                                    </div>

                                    <div class="d-flex justify-content-between align-items-center border-top pt-4">
                                        <a href="{{ route('client.orders.index') }}" class="btn btn-minimal-outline">
                                            Quay lại
                                        </a>
                                        <button type="submit" class="btn btn-minimal-dark">
                                            Gửi yêu cầu hủy & hoàn tiền
                                        </button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

