@extends('electro.layout')

@section('title', 'Tài khoản của tôi')

@section('content')
<div class="section">
    <div class="container">
        <div class="row">
            <!-- Sidebar Menu -->
            @include('electro.account._sidebar')

            <!-- Main Content -->
            <div class="col-md-9">
                <div class="section-title">
                    <h3 class="title">Thông tin tài khoản</h3>
                </div>

                <div class="account-info">
                    @if(session('success'))
                        <div class="alert alert-success" style="margin-bottom: 20px;">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger" style="margin-bottom: 20px;">
                            {{ session('error') }}
                        </div>
                    @endif

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <strong>Họ và tên:</strong>
                                <p>{{ $user->name ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <strong>Email:</strong>
                                <p>{{ $user->email ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <strong>Số điện thoại:</strong>
                                <p>{{ $user->phone ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="info-item">
                                <strong>Địa chỉ:</strong>
                                <p>{{ $user->address ?? 'Chưa cập nhật' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-12">
                            <button class="btn btn-primary" onclick="toggleEditMode()">
                                <i class="fa fa-edit"></i> Chỉnh sửa thông tin
                            </button>
                        </div>
                    </div>

                    <!-- Edit Form (Hidden by default) -->
                    <div id="edit-form" style="display: none; margin-top: 20px;">
                        <form action="{{ route('client.account.update') }}" method="POST">
                            @csrf
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="name">Họ và tên *</label>
                                        <input type="text" class="form-control" id="name" name="name" 
                                               value="{{ old('name', $user->name) }}" required>
                                        @error('name')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="form-group">
                                        <label for="phone">Số điện thoại</label>
                                        <input type="text" class="form-control" id="phone" name="phone" 
                                               value="{{ old('phone', $user->phone) }}">
                                        @error('phone')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="form-group">
                                        <label for="address">Địa chỉ</label>
                                        <textarea class="form-control" id="address" name="address" 
                                                  rows="3" placeholder="Nhập địa chỉ của bạn">{{ old('address', $user->address) }}</textarea>
                                        @error('address')
                                            <small class="text-danger">{{ $message }}</small>
                                        @enderror
                                    </div>
                                </div>
                            </div>

                            <div class="row">
                                <div class="col-md-12">
                                    <button type="submit" class="btn btn-success">
                                        <i class="fa fa-save"></i> Lưu thay đổi
                                    </button>
                                    <button type="button" class="btn btn-secondary" onclick="toggleEditMode()">
                                        <i class="fa fa-times"></i> Hủy
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                    <div class="row mb-4">
                        <div class="col-md-6">
                            <div class="info-item">
                                <strong>Ngày tham gia:</strong>
                                <p>{{ $user->created_at ? $user->created_at->format('d/m/Y') : 'N/A' }}</p>
                            </div>
                        </div>
                    </div>

                    <div class="account-stats mt-4">
                        <div class="row">
                            <div class="col-md-4">
                                <div class="stat-card text-center p-3" style="border: 1px solid #ddd; border-radius: 5px;">
                                    <h3 class="text-primary">{{ $user->orders()->count() ?? 0 }}</h3>
                                    <p class="mb-0">Đơn hàng</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card text-center p-3" style="border: 1px solid #ddd; border-radius: 5px;">
                                    <h3 class="text-success">
                                        @php
                                            $totalSpent = $user->orders()->where('status', 'completed')->sum('total') ?? 0;
                                        @endphp
                                        {{ number_format($totalSpent, 0, ',', '.') }} ₫
                                    </h3>
                                    <p class="mb-0">Tổng chi tiêu</p>
                                </div>
                            </div>
                            <div class="col-md-4">
                                <div class="stat-card text-center p-3" style="border: 1px solid #ddd; border-radius: 5px;">
                                    <h3 class="text-warning">
                                        @php
                                            // Đếm public coupons
                                            $publicCount = \App\Models\Coupon::where(function($q) {
                                                $q->where('type', 'public')->orWhereNull('type');
                                            })
                                            ->where(function($q) {
                                                $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                                            })
                                            ->count();
                                            
                                            // Đếm private coupons của user
                                            $privateCount = \App\Models\Coupon::where('type', 'private')
                                                ->whereHas('users', function($q) use ($user) {
                                                    $q->where('user_id', $user->id);
                                                })
                                                ->where(function($q) {
                                                    $q->whereNull('expires_at')->orWhere('expires_at', '>', now());
                                                })
                                                ->count();
                                            
                                            $couponCount = $publicCount + $privateCount;
                                        @endphp
                                        {{ $couponCount }}
                                    </h3>
                                    <p class="mb-0">Mã khuyến mãi</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
.info-item {
    padding: 15px;
    background: #f8f9fa;
    border-radius: 5px;
    margin-bottom: 15px;
}

.info-item strong {
    display: block;
    margin-bottom: 5px;
    color: #666;
}

.info-item p {
    margin: 0;
    font-size: 16px;
    color: #333;
}

.stat-card {
    background: #fff;
}

.stat-card h3 {
    margin: 0;
    font-size: 24px;
    font-weight: bold;
}

}

.stat-card p {
    margin: 5px 0 0 0;
    color: #666;
    font-size: 14px;
}

#edit-form {
    background: #f8f9fa;
    padding: 20px;
    border-radius: 5px;
    border: 1px solid #ddd;
}

#edit-form .form-control {
    border: 1px solid #ddd;
    padding: 10px;
    border-radius: 4px;
}

#edit-form .form-group {
    margin-bottom: 15px;
}

#edit-form label {
    display: block;
    margin-bottom: 5px;
    font-weight: 600;
    color: #333;
}

.btn {
    padding: 10px 20px;
    border-radius: 4px;
    border: none;
    cursor: pointer;
    font-weight: 500;
}

.btn-primary {
    background: #D10024;
    color: #fff;
}

.btn-primary:hover {
    background: #a8001c;
}

.btn-success {
    background: #28a745;
    color: #fff;
}

.btn-success:hover {
    background: #218838;
}

.btn-secondary {
    background: #6c757d;
    color: #fff;
    margin-left: 10px;
}

.btn-secondary:hover {
    background: #5a6268;
}
</style>

<script>
function toggleEditMode() {
    const editForm = document.getElementById('edit-form');
    if (editForm.style.display === 'none') {
        editForm.style.display = 'block';
    } else {
        editForm.style.display = 'none';
    }
}

// Auto-show edit form if there are errors
@if($errors->any())
    document.addEventListener('DOMContentLoaded', function() {
        document.getElementById('edit-form').style.display = 'block';
    });
@endif
</script>
@endsection
