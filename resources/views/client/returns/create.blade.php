@extends('electro.layout')

@section('content')
<style>
    /* Minimalist Styles */
    .return-page {
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
    }
    .form-control {
        border-radius: 0;
        border: 1px solid #ddd;
        padding: 12px;
        font-size: 0.9rem;
    }
    .form-control:focus {
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
    .method-selector {
        display: flex;
        gap: 20px;
        margin-top: 10px;
    }
    .form-check-input:checked {
        background-color: #000;
        border-color: #000;
    }
    .img-preview-minimal {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border: 1px solid #eee;
    }
    .info-box {
        background-color: #f9f9f9;
        border-left: 3px solid #000;
        padding: 20px;
        font-size: 0.9rem;
    }
    .invalid-feedback {
        display: none;
        color: #dc3545 !important;
        font-size: 0.875rem;
        margin-top: 0.25rem;
        width: 100%;
    }
    .form-control.is-invalid ~ .invalid-feedback {
        display: block;
    }
    .form-control.is-invalid {
        border-color: #dc3545 !important;
    }
</style>

<div class="container return-page py-5">
    <div class="row justify-content-center">
        <div class="col-lg-10">
            <h2 class="minimal-title mb-5">Yêu cầu hoàn trả</h2>

            <div class="row">
                <div class="col-md-4">
                    <div class="minimal-card card">
                        <div class="minimal-card-header">Tóm tắt đơn hàng</div>
                        <div class="card-body py-4">
                            <p class="mb-2 small text-muted text-uppercase">Mã đơn hàng</p>
                            <h5 class="fw-bold mb-4">#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h5>
                            
                            <p class="mb-2 small text-muted text-uppercase">Ngày đặt</p>
                            <p class="mb-4">{{ $order->created_at->format('d.m.Y') }}</p>
                            
                            <p class="mb-2 small text-muted text-uppercase">Tổng giá trị</p>
                            <p class="h4 fw-bold">{{ number_format($order->total, 0, ',', '.') }}₫</p>
                            
                            <hr class="my-4">
                            <span class="badge rounded-0 bg-dark px-3 py-2 text-uppercase">{{ $order->status_label }}</span>
                        </div>
                    </div>

                    <div class="info-box mb-4">
                        <p class="fw-bold mb-2">CHÍNH SÁCH</p>
                        <ul class="ps-3 mb-0 text-muted small">
                            <li>Áp dụng trong vòng 7 ngày kể từ khi nhận hàng.</li>
                            <li>Sản phẩm phải còn nguyên trạng thái ban đầu.</li>
                            <li>Xử lý yêu cầu trong 24-48 giờ làm việc.</li>
                        </ul>
                    </div>
                </div>

                <div class="col-md-8">
                    <div class="minimal-card card">
                        <div class="minimal-card-header">Chi tiết hoàn trả</div>
                        <div class="card-body p-4">
                            <form action="{{ route('client.orders.return.store', $order) }}" method="POST" enctype="multipart/form-data" id="returnForm">
                                @csrf

                                <div class="mb-4">
                                    <label for="contact_phone" class="form-label">Điện thoại liên hệ *</label>
                                    <input type="text" class="form-control @error('contact_phone') is-invalid @enderror" 
                                           id="contact_phone" name="contact_phone" 
                                           value="{{ old('contact_phone', $order->shipping_phone) }}">
                                    @error('contact_phone')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div class="invalid-feedback" id="contact_phone_error"></div>
                                </div>

                                <div class="mb-4">
                                    <label class="form-label">Phương thức hoàn tiền *</label>
                                    <div class="method-selector">
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="refund_method" id="refund_bank" value="Ngân hàng" {{ old('refund_method', 'Ngân hàng') == 'Ngân hàng' ? 'checked' : '' }}>
                                            <label class="form-check-label small" for="refund_bank">NGÂN HÀNG</label>
                                        </div>
                                    </div>
                                </div>

                                <div id="bankFields" style="display: none;" class="p-3 border mb-4 bg-light">
                                    <div class="mb-3">
                                        <label for="bank_name" class="form-label">Tên ngân hàng</label>
                                        <input type="text" class="form-control @error('bank_name') is-invalid @enderror" id="bank_name" name="bank_name" value="{{ old('bank_name') }}">
                                        @error('bank_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                        <div class="invalid-feedback" id="bank_name_error"></div>
                                    </div>
                                    <div class="row">
                                        <div class="col-md-6 mb-3">
                                            <label for="bank_account_number" class="form-label">Số tài khoản</label>
                                            <input type="text" class="form-control @error('bank_account_number') is-invalid @enderror" id="bank_account_number" name="bank_account_number" value="{{ old('bank_account_number') }}">
                                            @error('bank_account_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            <div class="invalid-feedback" id="bank_account_number_error"></div>
                                        </div>
                                        <div class="col-md-6 mb-3">
                                            <label for="bank_account_name" class="form-label">Tên chủ tài khoản</label>
                                            <input type="text" class="form-control @error('bank_account_name') is-invalid @enderror" id="bank_account_name" name="bank_account_name" value="{{ old('bank_account_name') }}">
                                            @error('bank_account_name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                            <div class="invalid-feedback" id="bank_account_name_error"></div>
                                        </div>
                                    </div>
                                </div>

                                <div class="mb-4">
                                    <label for="reason" class="form-label">Lý do hoàn trả *</label>
                                    <select class="form-control @error('reason') is-invalid @enderror" 
                                            id="reason" name="reason">
                                        <option value="">-- Chọn lý do hoàn trả --</option>
                                        <option value="Sản phẩm bị lỗi/hư hỏng" {{ old('reason') == 'Sản phẩm bị lỗi/hư hỏng' ? 'selected' : '' }}>Sản phẩm bị lỗi/hư hỏng</option>
                                        <option value="Sản phẩm không đúng mô tả" {{ old('reason') == 'Sản phẩm không đúng mô tả' ? 'selected' : '' }}>Sản phẩm không đúng mô tả</option>
                                        <option value="Nhận sai sản phẩm" {{ old('reason') == 'Nhận sai sản phẩm' ? 'selected' : '' }}>Nhận sai sản phẩm</option>
                                        <option value="Sản phẩm bị hư hại trong vận chuyển" {{ old('reason') == 'Sản phẩm bị hư hại trong vận chuyển' ? 'selected' : '' }}>Sản phẩm bị hư hại trong vận chuyển</option>
                                        <option value="Đổi ý, không muốn mua nữa" {{ old('reason') == 'Đổi ý, không muốn mua nữa' ? 'selected' : '' }}>Đổi ý, không muốn mua nữa</option>
                                        <option value="Khác" {{ old('reason') == 'Khác' ? 'selected' : '' }}>Khác (ghi rõ trong ghi chú)</option>
                                    </select>
                                    @error('reason')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div class="invalid-feedback" id="reason_error"></div>
                                </div>

                                <div class="mb-5">
                                    <label for="images" class="form-label">Hình ảnh minh chứng *</label>
                                    <input type="file" class="form-control @error('images.*') is-invalid @enderror" 
                                           id="images" name="images[]" accept="image/*" multiple>
                                    <small class="text-muted d-block mt-1">Tối đa 5 ảnh. Dung lượng không quá 2MB/ảnh.</small>
                                    @error('images.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div class="invalid-feedback" id="images_error"></div>
                                    <div id="imagePreview" class="d-flex flex-wrap gap-2 mt-3"></div>
                                </div>

                                <div class="d-flex justify-content-between align-items-center border-top pt-4">
                                    <a href="{{ route('client.orders.show', $order) }}" class="btn btn-minimal-outline">
                                        Quay lại
                                    </a>
                                    <button type="submit" class="btn btn-minimal-dark" id="submitBtn">
                                        Xác nhận gửi yêu cầu
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

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const refundMethods = document.querySelectorAll('input[name="refund_method"]');
    const bankFields = document.getElementById('bankFields');
    const returnForm = document.getElementById('returnForm');
    
    function toggleBank(value) {
        if (value === 'Ngân hàng') {
            bankFields.style.display = 'block';
        } else {
            bankFields.style.display = 'none';
        }
    }

    refundMethods.forEach(radio => {
        radio.addEventListener('change', (e) => toggleBank(e.target.value));
    });

    const selectedMethod = document.querySelector('input[name="refund_method"]:checked');
    if (selectedMethod) toggleBank(selectedMethod.value);

    // Image preview minimalist
    const imageInput = document.getElementById('images');
    const imagePreview = document.getElementById('imagePreview');
    
    imageInput.addEventListener('change', function(e) {
        imagePreview.innerHTML = '';
        const files = Array.from(e.target.files);
        
        if (files.length > 5) {
            showError('images', 'Bạn chỉ được tải lên tối đa 5 ảnh');
            e.target.value = '';
            return;
        }
        
        // Clear error when valid files selected
        clearError('images');
        
        files.forEach(file => {
            // Validate file size
            if (file.size > 2048 * 1024) {
                showError('images', 'Kích thước ảnh không được vượt quá 2MB');
                e.target.value = '';
                imagePreview.innerHTML = '';
                return;
            }
            
            const reader = new FileReader();
            reader.onload = function(event) {
                const img = document.createElement('img');
                img.src = event.target.result;
                img.className = 'img-preview-minimal';
                imagePreview.appendChild(img);
            };
            reader.readAsDataURL(file);
        });
    });

    // Form validation
    returnForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Clear all previous errors
        clearAllErrors();
        
        let isValid = true;
        
        // Validate contact phone
        const contactPhone = document.getElementById('contact_phone').value.trim();
        if (!contactPhone) {
            showError('contact_phone', 'Vui lòng nhập số điện thoại liên hệ');
            isValid = false;
        } else if (contactPhone.length < 10 || contactPhone.length > 11) {
            showError('contact_phone', 'Số điện thoại phải có 10-11 chữ số');
            isValid = false;
        }
        
        // Validate bank fields if refund method is bank
        const refundMethod = document.querySelector('input[name="refund_method"]:checked');
        if (refundMethod && refundMethod.value === 'Ngân hàng') {
            const bankName = document.getElementById('bank_name').value.trim();
            const bankAccountNumber = document.getElementById('bank_account_number').value.trim();
            const bankAccountName = document.getElementById('bank_account_name').value.trim();
            
            if (!bankName) {
                showError('bank_name', 'Vui lòng nhập tên ngân hàng');
                isValid = false;
            }
            
            if (!bankAccountNumber) {
                showError('bank_account_number', 'Vui lòng nhập số tài khoản');
                isValid = false;
            }
            
            if (!bankAccountName) {
                showError('bank_account_name', 'Vui lòng nhập tên chủ tài khoản');
                isValid = false;
            }
        }
        
        // Validate reason
        const reason = document.getElementById('reason').value;
        if (!reason) {
            showError('reason', 'Vui lòng chọn lý do hoàn trả');
            isValid = false;
        }
        
        // Validate images
        const images = document.getElementById('images').files;
        if (images.length === 0) {
            showError('images', 'Vui lòng tải lên ít nhất 1 ảnh minh chứng');
            isValid = false;
        } else if (images.length > 5) {
            showError('images', 'Bạn chỉ được tải lên tối đa 5 ảnh');
            isValid = false;
        }
        
        if (isValid) {
            returnForm.submit();
        } else {
            // Scroll to first error
            const firstError = document.querySelector('.is-invalid');
            if (firstError) {
                firstError.scrollIntoView({ behavior: 'smooth', block: 'center' });
            }
        }
    });
    
    function showError(fieldId, message) {
        const field = document.getElementById(fieldId);
        const errorDiv = document.getElementById(fieldId + '_error');
        
        if (field) {
            field.classList.add('is-invalid');
        }
        
        if (errorDiv) {
            errorDiv.textContent = message;
            errorDiv.style.display = 'block';
        }
    }
    
    function clearError(fieldId) {
        const field = document.getElementById(fieldId);
        const errorDiv = document.getElementById(fieldId + '_error');
        
        if (field) {
            field.classList.remove('is-invalid');
        }
        
        if (errorDiv) {
            errorDiv.textContent = '';
            errorDiv.style.display = 'none';
        }
    }
    
    function clearAllErrors() {
        const fields = ['contact_phone', 'bank_name', 'bank_account_number', 'bank_account_name', 'reason', 'images'];
        fields.forEach(fieldId => clearError(fieldId));
    }
});
</script>
@endpush
@endsection