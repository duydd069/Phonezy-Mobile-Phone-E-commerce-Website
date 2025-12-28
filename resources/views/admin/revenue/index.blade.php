@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <h2 class="m-0"><i class="bi bi-graph-up me-2"></i>Báo Cáo Doanh Thu</h2>
    </div>

    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            {{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif

    {{-- Filter Form --}}
    <div class="card mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('admin.revenue.index') }}" class="row g-3">
                <div class="col-md-3">
                    <label for="start_date" class="form-label">Từ ngày</label>
                    <input type="date" name="start_date" id="start_date" class="form-control"
                           value="{{ $start->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label for="end_date" class="form-label">Đến ngày</label>
                    <input type="date" name="end_date" id="end_date" class="form-control"
                           value="{{ $end->format('Y-m-d') }}">
                </div>
                <div class="col-md-3">
                    <label for="period" class="form-label">Khoảng thời gian</label>
                    <select name="period" id="period" class="form-select">
                        <option value="day" {{ $period === 'day' ? 'selected' : '' }}>Hôm nay</option>
                        <option value="week" {{ $period === 'week' ? 'selected' : '' }}>7 ngày qua</option>
                        <option value="month" {{ $period === 'month' ? 'selected' : '' }}>30 ngày qua</option>
                        <option value="year" {{ $period === 'year' ? 'selected' : '' }}>Năm nay</option>
                        <option value="all" {{ $period === 'all' ? 'selected' : '' }}>Tất cả</option>
                    </select>
                </div>
                <div class="col-md-3 d-flex align-items-end">
                    <button type="submit" class="btn btn-primary me-2">
                        <i class="bi bi-search me-1"></i>Lọc
                    </button>
                    <a href="{{ route('admin.revenue.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Summary Cards --}}
    <style>
        .revenue-card-value {
            font-size: clamp(1rem, 2.5vw, 1.15rem);
            font-weight: 600;
            line-height: 1.2;
            white-space: nowrap;
            overflow: visible;
        }
        .revenue-card-icon {
            font-size: 1.25rem;
            opacity: 0.6;
            flex-shrink: 0;
        }
        @media (max-width: 768px) {
            .revenue-card-value {
                font-size: 0.95rem;
            }
            .revenue-card-icon {
                font-size: 1rem;
            }
        }
    </style>
    <div class="row mb-4">
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card text-white bg-primary h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title text-white-50 mb-2" style="font-size: 0.875rem;">Tổng Doanh Thu</h6>
                    <div class="d-flex justify-content-between align-items-baseline flex-grow-1">
                        <div class="revenue-card-value text-white">
                            {{ number_format((float)($totalRevenue ?? 0), 0, ',', '.') }} ₫
                        </div>
                        <div class="revenue-card-icon ms-2">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card text-white bg-success h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title text-white-50 mb-2" style="font-size: 0.875rem;">Tổng Đơn Hàng</h6>
                    <div class="d-flex justify-content-between align-items-baseline flex-grow-1">
                        <div class="revenue-card-value text-white">
                            {{ number_format((int)($totalOrders ?? 0), 0, ',', '.') }}
                        </div>
                        <div class="revenue-card-icon ms-2">
                            <i class="bi bi-cart-check"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card text-white bg-info h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title text-white-50 mb-2" style="font-size: 0.875rem;">Đơn Đã Thanh Toán</h6>
                    <div class="d-flex justify-content-between align-items-baseline flex-grow-1">
                        <div class="revenue-card-value text-white">
                            {{ number_format((int)($paidOrders ?? 0), 0, ',', '.') }}
                        </div>
                        <div class="revenue-card-icon ms-2">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card text-white bg-warning h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title text-white-50 mb-2" style="font-size: 0.875rem;">Tổng Giảm Giá</h6>
                    <div class="d-flex justify-content-between align-items-baseline flex-grow-1">
                        <div class="revenue-card-value text-white">
                            {{ number_format((float)($totalDiscount ?? 0), 0, ',', '.') }} ₫
                        </div>
                        <div class="revenue-card-icon ms-2">
                            <i class="bi bi-tag"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Return Statistics Cards --}}
    <div class="row mb-4">
        <div class="col-12">
            <h5 class="mb-3"><i class="bi bi-arrow-return-left me-2"></i>Thống Kê Đơn Hàng Hoàn Trả</h5>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card text-white bg-danger h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title text-white-50 mb-2" style="font-size: 0.875rem;">Tổng Yêu Cầu Hoàn Trả</h6>
                    <div class="d-flex justify-content-between align-items-baseline flex-grow-1">
                        <div class="revenue-card-value text-white">
                            {{ number_format((int)($returnStats['total_returns'] ?? 0), 0, ',', '.') }}
                        </div>
                        <div class="revenue-card-icon ms-2">
                            <i class="bi bi-arrow-return-left"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card text-white bg-secondary h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title text-white-50 mb-2" style="font-size: 0.875rem;">Đã Hoàn Tiền</h6>
                    <div class="d-flex justify-content-between align-items-baseline flex-grow-1">
                        <div class="revenue-card-value text-white">
                            {{ number_format((int)($returnStats['refunded_returns'] ?? 0), 0, ',', '.') }}
                        </div>
                        <div class="revenue-card-icon ms-2">
                            <i class="bi bi-check-circle"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card text-white bg-dark h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title text-white-50 mb-2" style="font-size: 0.875rem;">Tổng Giá Trị Hoàn Trả</h6>
                    <div class="d-flex justify-content-between align-items-baseline flex-grow-1">
                        <div class="revenue-card-value text-white">
                            {{ number_format((float)($returnStats['total_refunded_amount'] ?? 0), 0, ',', '.') }} ₫
                        </div>
                        <div class="revenue-card-icon ms-2">
                            <i class="bi bi-currency-dollar"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-3 col-sm-6 mb-3 mb-md-0">
            <div class="card text-white bg-warning h-100">
                <div class="card-body d-flex flex-column">
                    <h6 class="card-title text-white-50 mb-2" style="font-size: 0.875rem;">Đang Chờ Xử Lý</h6>
                    <div class="d-flex justify-content-between align-items-baseline flex-grow-1">
                        <div class="revenue-card-value text-white">
                            {{ number_format((int)($returnStats['pending_returns'] ?? 0), 0, ',', '.') }}
                        </div>
                        <div class="revenue-card-icon ms-2">
                            <i class="bi bi-clock-history"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Charts Row --}}
    <div class="row mb-4">
        {{-- Daily Revenue Chart --}}
        <div class="col-md-8">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-graph-up me-2"></i>Doanh Thu Theo Ngày</h5>
                </div>
                <div class="card-body">
                    <canvas id="dailyRevenueChart" height="100"></canvas>
                </div>
            </div>
        </div>

        {{-- Revenue by Status --}}
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-pie-chart me-2"></i>Doanh Thu Theo Trạng Thái</h5>
                </div>
                <div class="card-body">
                    <canvas id="revenueByStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Monthly Stats Chart --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Thống Kê 12 Tháng Gần Nhất</h5>
                </div>
                <div class="card-body">
                    <canvas id="monthlyStatsChart" height="60"></canvas>
                </div>
            </div>
        </div>
    </div>

    {{-- Revenue by Product and Category --}}
    <div class="row mb-4">
        {{-- Top Products --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-box-seam me-2"></i>Top 10 Sản Phẩm Bán Chạy</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Sản Phẩm</th>
                                    <th>Số Lượng</th>
                                    <th>Doanh Thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($revenueByProduct as $index => $product)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $product->product_name }}</td>
                                        <td>{{ number_format($product->quantity_sold, 0, ',', '.') }}</td>
                                        <td class="text-success fw-bold">
                                            {{ number_format($product->revenue, 0, ',', '.') }} ₫
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        {{-- Revenue by Category --}}
        <div class="col-md-6">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-grid me-2"></i>Doanh Thu Theo Danh Mục</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Danh Mục</th>
                                    <th>Số Lượng</th>
                                    <th>Doanh Thu</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($revenueByCategory as $index => $category)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>{{ $category->category_name }}</td>
                                        <td>{{ number_format($category->quantity_sold, 0, ',', '.') }}</td>
                                        <td class="text-success fw-bold">
                                            {{ number_format($category->revenue, 0, ',', '.') }} ₫
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="4" class="text-center text-muted">Không có dữ liệu</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Return Details Table --}}
    <div class="row mb-4">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h5 class="mb-0"><i class="bi bi-list-ul me-2"></i>Chi Tiết Đơn Hàng Hoàn Trả (10 đơn gần nhất)</h5>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover">
                            <thead>
                                <tr>
                                    <th>STT</th>
                                    <th>Mã Hoàn Trả</th>
                                    <th>ID Đơn Hàng</th>
                                    <th>Giá Trị Đơn Hàng</th>
                                    <th>Trạng Thái</th>
                                    <th>Ngày Hoàn Tiền</th>
                                    <th>Ngày Tạo</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($returnStats['return_details'] ?? [] as $index => $return)
                                    <tr>
                                        <td>{{ $index + 1 }}</td>
                                        <td>
                                            <a href="{{ route('admin.returns.show', $return['id']) }}" class="text-decoration-none">
                                                {{ $return['return_code'] }}
                                            </a>
                                        </td>
                                        <td>#{{ $return['order_id'] }}</td>
                                        <td class="text-danger fw-bold">
                                            {{ number_format($return['order_total'], 0, ',', '.') }} ₫
                                        </td>
                                        <td>
                                            @php
                                                $statusColors = [
                                                    'Chưa giải quyết' => 'warning',
                                                    'Thông qua' => 'info',
                                                    'Từ chối' => 'danger',
                                                    'Đang vận chuyển' => 'primary',
                                                    'Đã nhận' => 'secondary',
                                                    'Đã hoàn tiền' => 'success',
                                                ];
                                                $color = $statusColors[$return['status']] ?? 'secondary';
                                            @endphp
                                            <span class="badge bg-{{ $color }}">{{ $return['status'] }}</span>
                                        </td>
                                        <td>{{ $return['refunded_at'] ?? '-' }}</td>
                                        <td>{{ $return['created_at'] }}</td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="7" class="text-center text-muted">Không có dữ liệu hoàn trả</td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

{{-- Chart.js CDN --}}
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>

<script>
    // Daily Revenue Chart
    const dailyRevenueData = @json($dailyRevenue);
    const dailyLabels = dailyRevenueData.map(item => item.date);
    const dailyRevenueValues = dailyRevenueData.map(item => item.revenue);
    const dailyOrdersValues = dailyRevenueData.map(item => item.orders);
    const dailyRefundedAmountValues = dailyRevenueData.map(item => item.refunded_amount || 0);
    const dailyRefundedCountValues = dailyRevenueData.map(item => item.refunded_count || 0);

    const dailyRevenueCtx = document.getElementById('dailyRevenueChart').getContext('2d');
    new Chart(dailyRevenueCtx, {
        type: 'line',
        data: {
            labels: dailyLabels,
            datasets: [{
                label: 'Doanh Thu (₫)',
                data: dailyRevenueValues,
                borderColor: 'rgb(75, 192, 192)',
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                tension: 0.1,
                yAxisID: 'y'
            }, {
                label: 'Số Đơn Hàng',
                data: dailyOrdersValues,
                borderColor: 'rgb(255, 99, 132)',
                backgroundColor: 'rgba(255, 99, 132, 0.2)',
                tension: 0.1,
                yAxisID: 'y1'
            }, {
                label: 'Giá Trị Hoàn Trả (₫)',
                data: dailyRefundedAmountValues,
                borderColor: 'rgb(220, 53, 69)',
                backgroundColor: 'rgba(220, 53, 69, 0.2)',
                borderDash: [5, 5],
                tension: 0.1,
                yAxisID: 'y'
            }, {
                label: 'Số Đơn Hoàn Trả',
                data: dailyRefundedCountValues,
                borderColor: 'rgb(255, 193, 7)',
                backgroundColor: 'rgba(255, 193, 7, 0.2)',
                borderDash: [5, 5],
                tension: 0.1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            interaction: {
                mode: 'index',
                intersect: false,
            },
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + ' ₫';
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Revenue by Status Chart
    const revenueByStatusData = @json($revenueByStatus);
    const statusLabels = revenueByStatusData.map(item => {
        const statusMap = {
            'pending': 'Chờ xử lý',
            'processing': 'Đang xử lý',
            'completed': 'Hoàn thành',
            'cancelled': 'Đã hủy'
        };
        return statusMap[item.status] || item.status;
    });
    const statusRevenueValues = revenueByStatusData.map(item => parseFloat(item.revenue));

    const revenueByStatusCtx = document.getElementById('revenueByStatusChart').getContext('2d');
    new Chart(revenueByStatusCtx, {
        type: 'doughnut',
        data: {
            labels: statusLabels,
            datasets: [{
                data: statusRevenueValues,
                backgroundColor: [
                    'rgba(255, 206, 86, 0.8)',
                    'rgba(54, 162, 235, 0.8)',
                    'rgba(75, 192, 192, 0.8)',
                    'rgba(255, 99, 132, 0.8)'
                ],
                borderColor: [
                    'rgba(255, 206, 86, 1)',
                    'rgba(54, 162, 235, 1)',
                    'rgba(75, 192, 192, 1)',
                    'rgba(255, 99, 132, 1)'
                ],
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            plugins: {
                legend: {
                    position: 'bottom',
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            return context.label + ': ' + new Intl.NumberFormat('vi-VN').format(context.parsed) + ' ₫';
                        }
                    }
                }
            }
        }
    });

    // Monthly Stats Chart
    const monthlyStatsData = @json($monthlyStats);
    const monthlyLabels = monthlyStatsData.map(item => item.month_name);
    const monthlyRevenueValues = monthlyStatsData.map(item => item.revenue);
    const monthlyOrdersValues = monthlyStatsData.map(item => item.orders);

    const monthlyStatsCtx = document.getElementById('monthlyStatsChart').getContext('2d');
    new Chart(monthlyStatsCtx, {
        type: 'bar',
        data: {
            labels: monthlyLabels,
            datasets: [{
                label: 'Doanh Thu (₫)',
                data: monthlyRevenueValues,
                backgroundColor: 'rgba(54, 162, 235, 0.6)',
                borderColor: 'rgba(54, 162, 235, 1)',
                borderWidth: 1,
                yAxisID: 'y'
            }, {
                label: 'Số Đơn Hàng',
                data: monthlyOrdersValues,
                backgroundColor: 'rgba(255, 99, 132, 0.6)',
                borderColor: 'rgba(255, 99, 132, 1)',
                borderWidth: 1,
                yAxisID: 'y1'
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            scales: {
                y: {
                    type: 'linear',
                    display: true,
                    position: 'left',
                    ticks: {
                        callback: function(value) {
                            return new Intl.NumberFormat('vi-VN').format(value) + ' ₫';
                        }
                    }
                },
                y1: {
                    type: 'linear',
                    display: true,
                    position: 'right',
                    grid: {
                        drawOnChartArea: false,
                    },
                }
            }
        }
    });

    // Auto-update date range based on period selection
    document.getElementById('period').addEventListener('change', function() {
        const period = this.value;
        const endDate = new Date();
        let startDate = new Date();

        switch(period) {
            case 'day':
                startDate = new Date(endDate);
                break;
            case 'week':
                startDate.setDate(endDate.getDate() - 7);
                break;
            case 'month':
                startDate.setDate(endDate.getDate() - 30);
                break;
            case 'year':
                startDate.setFullYear(endDate.getFullYear(), 0, 1);
                break;
            case 'all':
                startDate = new Date('2020-01-01');
                break;
        }

        document.getElementById('start_date').value = startDate.toISOString().split('T')[0];
        document.getElementById('end_date').value = endDate.toISOString().split('T')[0];
    });
</script>
@endsection

