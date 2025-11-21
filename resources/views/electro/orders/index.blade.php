@extends('electro.layout')

@section('title', 'My Orders')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">My Orders</h3>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger">{{ session('error') }}</div>
                    @endif

                    {{-- Filter --}}
                    <div class="filter-widget mb-4">
                        <form method="GET" action="{{ route('client.orders.index') }}" class="row g-2">
                            <div class="col-md-3">
                                <select name="status" class="form-control">
                                    <option value="">All Status</option>
                                    <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                                    <option value="processing" {{ request('status') === 'processing' ? 'selected' : '' }}>Processing</option>
                                    <option value="completed" {{ request('status') === 'completed' ? 'selected' : '' }}>Completed</option>
                                    <option value="cancelled" {{ request('status') === 'cancelled' ? 'selected' : '' }}>Cancelled</option>
                                </select>
                            </div>
                            <div class="col-md-3">
                                <button type="submit" class="primary-btn">Filter</button>
                                @if(request('status'))
                                    <a href="{{ route('client.orders.index') }}" class="primary-btn" style="margin-left: 10px;">Clear Filter</a>
                                @endif
                            </div>
                        </form>
                    </div>

                    {{-- Orders List --}}
                    @forelse($orders as $order)
                        <div class="order-item" style="border: 1px solid #ddd; padding: 20px; margin-bottom: 20px; border-radius: 5px;">
                            <div class="row">
                                <div class="col-md-8">
                                    <h4>
                                        Order #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}
                                        <span class="badge {{ $order->status_badge_class }}" style="margin-left: 10px;">
                                            {{ $order->status_label }}
                                        </span>
                                    </h4>
                                    <p class="text-muted">
                                        <strong>Order Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}
                                    </p>
                                    <p>
                                        <strong>Total:</strong> 
                                        <span style="color: #F7941D; font-size: 18px; font-weight: bold;">
                                            {{ number_format($order->total, 0, ',', '.') }} ₫
                                        </span>
                                    </p>
                                    <p>
                                        <strong>Payment Method:</strong> 
                                        {{ config("checkout.payment_methods.{$order->payment_method}", strtoupper($order->payment_method)) }}
                                    </p>
                                </div>
                                <div class="col-md-4 text-right">
                                    <a href="{{ route('client.orders.show', $order->id) }}" class="primary-btn">
                                        View Details
                                    </a>
                                </div>
                            </div>

                            {{-- Order Items --}}
                            <div class="order-products mt-3" style="border-top: 1px solid #eee; padding-top: 15px;">
                                <h5 style="font-size: 14px; color: #666;">Order Items:</h5>
                                <ul class="list-unstyled">
                                    @foreach($order->items->take(3) as $item)
                                        <li style="padding: 5px 0;">
                                            {{ $item->quantity }}x {{ $item->product_name }}
                                        </li>
                                    @endforeach
                                    @if($order->items->count() > 3)
                                        <li style="padding: 5px 0; color: #999;">
                                            ... and {{ $order->items->count() - 3 }} more items
                                        </li>
                                    @endif
                                </ul>
                            </div>
                        </div>
                    @empty
                        <div class="text-center" style="padding: 50px 0;">
                            <p style="font-size: 18px; color: #999;">You don't have any orders yet.</p>
                            <a href="{{ route('client.index') }}" class="primary-btn">Continue Shopping</a>
                        </div>
                    @endforelse

                    {{-- Pagination --}}
                    @if($orders->hasPages())
                        <div class="mt-4">
                            {{ $orders->links() }}
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
@endsection
