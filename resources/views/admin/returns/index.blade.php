@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">
            <i class="fa fa-undo"></i> Quản lý yêu cầu hoàn trả
        </h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Status Filter Buttons --}}
    @php
        $allStatuses = ['Chưa giải quyết', 'Thông qua', 'Từ chối', 'Đang vận chuyển', 'Đã nhận', 'Đã hoàn tiền'];
        $currentStatus = request('status');
    @endphp
    
    <div class="mb-3">
        <div class="btn-group flex-wrap" role="group">
            <a href="{{ route('admin.returns.index') }}" 
               class="btn btn-sm {{ !$currentStatus ? 'btn-primary' : 'btn-outline-primary' }}">
                Tất cả
            </a>
            @foreach($allStatuses as $status)
                <a href="{{ route('admin.returns.index', ['status' => $status]) }}" 
                   class="btn btn-sm {{ $currentStatus === $status ? 'btn-primary' : 'btn-outline-primary' }}">
                    {{ $status }}
                </a>
            @endforeach
        </div>
    </div>

    {{-- Search Form --}}
    <form method="GET" action="{{ route('admin.returns.index') }}" class="row g-2 mb-3">
        @if(request('status'))
            <input type="hidden" name="status" value="{{ request('status') }}">
        @endif
        <div class="col-auto">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" 
                   placeholder="Tìm theo mã hoàn trả hoặc mã đơn hàng..." style="min-width: 300px;">
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-primary">
                <i class="fa fa-search"></i> Tìm kiếm
            </button>
            @if(request('q'))
                <a href="{{ route('admin.returns.index', ['status' => request('status')]) }}" class="btn btn-outline-secondary">
                    <i class="fa fa-times"></i> Xóa
                </a>
            @endif
        </div>
    </form>

    {{-- Count --}}
    <div class="mb-2">
        <strong>Tổng: {{ number_format($returns->total()) }} yêu cầu</strong>
    </div>

    {{-- Returns Table --}}
    <div class="table-responsive">
        <table class="table table-striped table-hover align-middle">
            <thead class="table-dark">
                <tr>
                    <th>Mă hoàn trả</th>
                    <th>Mã đơn hàng</th>
                    <th>Khách hàng</th>
                    <th>Phương thức hoàn tiền</th>
                    <th>Trạng thái</th>
                    <th>Trạng thái vận chuyển</th>
                    <th>Ngày tạo</th>
                    <th>Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($returns as $return)
                    <tr>
                        <td><strong>{{ $return->return_code }}</strong></td>
                        <td>
                            <a href="{{ route('admin.orders.show', $return->order_id) }}" target="_blank">
                                #{{ str_pad($return->order_id, 6, '0', STR_PAD_LEFT) }}
                            </a>
                        </td>
                        <td>
                            <div>{{ $return->order->shipping_full_name }}</div>
                            <small class="text-muted">{{ $return->contact_phone }}</small>
                        </td>
                        <td>
                            <span class="badge bg-info">{{ $return->refund_method }}</span>
                            @if($return->refund_method === 'Ngân hàng')
                                <br><small class="text-muted">{{ $return->bank_name }}</small>
                            @endif
                        </td>
                        <td>
                            <span class="badge {{ $return->status_badge_class }}" style="font-size: 12px; padding: 6px 10px;">
                                {{ $return->status_label }}
                            </span>
                        </td>
                        <td>{{ $return->shipping_status_label }}</td>
                        <td>{{ $return->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.returns.show', $return) }}" class="btn btn-sm btn-primary">
                                <i class="fa fa-eye"></i> Xem
                            </a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-3">Không tìm thấy yêu cầu hoàn trả nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($returns->hasPages())
        <div class="mt-3">
            {{ $returns->links() }}
        </div>
    @endif
</div>
@endsection
