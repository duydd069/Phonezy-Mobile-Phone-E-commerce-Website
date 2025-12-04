@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">Order Management</h3>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filter and Search --}}
    <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-2 mb-3">
        <div class="col-auto">
            <input type="text" name="q" value="{{ request('q') }}" class="form-control" 
                   placeholder="Search by ID, name, phone, email...">
        </div>
        <div class="col-auto">
            <select name="status" class="form-select">
                <option value="">All Status</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
            </select>
        </div>
        <div class="col-auto">
            <select name="payment_status" class="form-select">
                <option value="">All Payment Status</option>
                <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('payment_status') === 'paid' ? 'selected' : '' }}>Paid</option>
                <option value="failed" {{ request('payment_status') === 'failed' ? 'selected' : '' }}>Failed</option>
            </select>
        </div>
        <div class="col-auto">
            <button type="submit" class="btn btn-outline-primary">Search</button>
            @if(request('q') || request('status') || request('payment_status'))
                <a href="{{ route('admin.orders.index') }}" class="btn btn-outline-secondary">Clear Filter</a>
            @endif
        </div>
    </form>

    {{-- Orders Table --}}
    <div class="table-responsive">
        <table class="table table-striped align-middle">
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Customer</th>
                    <th>Phone</th>
                    <th>Total</th>
                    <th>Status</th>
                    <th>Payment</th>
                    <th>Order Date</th>
                    <th>Actions</th>
                </tr>
            </thead>
            <tbody>
                @forelse($orders as $order)
                    <tr>
                        <td><strong>#{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</strong></td>
                        <td>
                            <div>{{ $order->shipping_full_name }}</div>
                            @if($order->shipping_email)
                                <small class="text-muted">{{ $order->shipping_email }}</small>
                            @endif
                            @if($order->user)
                                <small class="text-muted d-block">User ID: {{ $order->user_id }}</small>
                            @endif
                        </td>
                        <td>{{ $order->shipping_phone }}</td>
                        <td>
                            <strong style="color: #F7941D;">
                                {{ number_format($order->total, 0, ',', '.') }} ₫
                            </strong>
                        </td>
                        <td>
                            <span class="badge {{ $order->status_badge_class }}">
                                {{ $order->status_label }}
                            </span>
                        </td>
                        <td>
                            <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }}">
                                {{ $order->payment_status_label }}
                            </span>
                        </td>
                        <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                        <td>
                            <a href="{{ route('admin.orders.show', $order->id) }}" 
                               class="btn btn-sm btn-primary">View Details</a>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-3">No orders found.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Pagination --}}
    @if($orders->hasPages())
        <div class="mt-3">
            {{ $orders->links() }}
        </div>
    @endif
</div>
@endsection