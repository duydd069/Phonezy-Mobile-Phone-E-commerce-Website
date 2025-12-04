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

.stat-card p {
    margin: 5px 0 0 0;
    color: #666;
    font-size: 14px;
}
</style>
@endsection
