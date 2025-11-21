@extends('electro.layout')

@section('title', 'Order Details')

@section('content')
    <div class="section">
        <div class="container">
            <div class="row">
                <div class="col-md-12">
                    <div class="section-title">
                        <h3 class="title">Order Details #{{ str_pad($order->id, 6, '0', STR_PAD_LEFT) }}</h3>
                        <a href="{{ route('client.orders.index') }}" class="btn btn-default" style="margin-top: 10px;">
                            ← Back to Orders
                        </a>
                    </div>

                    @if(session('success'))
                        <div class="alert alert-success">{{ session('success') }}</div>
                    @endif

                    {{-- Order Status --}}
                    <div class="order-status-box" style="background: #f9f9f9; padding: 20px; border-radius: 5px; margin-bottom: 30px;">
                        <div class="row">
                            <div class="col-md-6">
                                <p style="margin-bottom: 5px;">
                                    <strong>Order Status:</strong>
                                    <span class="badge {{ $order->status_badge_class }}" style="font-size: 14px; padding: 8px 12px;">
                                        {{ $order->status_label }}
                                    </span>
                                </p>
                                <p style="margin-bottom: 5px;">
                                    <strong>Payment Status:</strong>
                                    <span class="badge {{ $order->payment_status === 'paid' ? 'bg-success' : 'bg-warning' }}" style="font-size: 14px; padding: 8px 12px;">
                                        {{ $order->payment_status_label }}
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 text-right">
                                <p style="margin-bottom: 5px;"><strong>Order Date:</strong> {{ $order->created_at->format('d/m/Y H:i') }}</p>
                                @if($order->updated_at != $order->created_at)
                                    <p style="margin-bottom: 5px;"><strong>Last Updated:</strong> {{ $order->updated_at->format('d/m/Y H:i') }}</p>
                                @endif
                            </div>
                        </div>
                    </div>

                    {{-- Order Information --}}
                    <div class="row">
                        <div class="col-md-8">
                            {{-- Shipping Information --}}
                            <div class="billing-details" style="margin-bottom: 30px;">
                                <div class="section-title">
                                    <h4 class="title">Shipping Information</h4>
                                </div>
                                <ul class="list-unstyled" style="line-height: 2;">
                                    <li><strong>Full Name:</strong> {{ $order->shipping_full_name }}</li>
                                    <li><strong>Phone:</strong> {{ $order->shipping_phone }}</li>
                                    @if($order->shipping_email)
                                        <li><strong>Email:</strong> {{ $order->shipping_email }}</li>
                                    @endif
                                    <li><strong>Address:</strong> {{ $order->shipping_address }}</li>
                                    @if($order->shipping_ward || $order->shipping_district || $order->shipping_city)
                                        <li>
                                            <strong>Area:</strong>
                                            {{ collect([$order->shipping_ward, $order->shipping_district, $order->shipping_city])->filter()->implode(', ') }}
                                        </li>
                                    @endif
                                    @if($order->notes)
                                        <li><strong>Notes:</strong> {{ $order->notes }}</li>
                                    @endif
                                </ul>
                            </div>

                            {{-- Order Items --}}
                            <div class="order-details">
                                <div class="section-title">
                                    <h4 class="title">Order Items</h4>
                                </div>
                                <div class="order-summary">
                                    <div class="order-col">
                                        <div><strong>PRODUCT</strong></div>
                                        <div><strong>UNIT PRICE</strong></div>
                                        <div><strong>QUANTITY</strong></div>
                                        <div><strong>TOTAL</strong></div>
                                    </div>

                                    @foreach($order->items as $item)
                                        <div class="order-col">
                                            <div>
                                                @if($item->product_image)
                                                    <img src="{{ $item->product_image }}" alt="{{ $item->product_name }}" 
                                                         style="width: 50px; height: 50px; object-fit: cover; margin-right: 10px; vertical-align: middle;">
                                                @endif
                                                {{ $item->product_name }}
                                            </div>
                                            <div>{{ number_format($item->unit_price, 0, ',', '.') }} ₫</div>
                                            <div>{{ $item->quantity }}</div>
                                            <div><strong>{{ number_format($item->total_price, 0, ',', '.') }} ₫</strong></div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>

                        <div class="col-md-4">
                            {{-- Order Summary --}}
                            <div class="order-summary" style="background: #f9f9f9; padding: 20px; border-radius: 5px;">
                                <div class="section-title">
                                    <h4 class="title">Order Summary</h4>
                                </div>
                                <ul class="list-unstyled" style="line-height: 2.5;">
                                    <li style="display: flex; justify-content: space-between;">
                                        <span>Subtotal:</span>
                                        <span>{{ number_format($order->subtotal, 0, ',', '.') }} ₫</span>
                                    </li>
                                    <li style="display: flex; justify-content: space-between;">
                                        <span>Shipping Fee:</span>
                                        <span>{{ number_format($order->shipping_fee, 0, ',', '.') }} ₫</span>
                                    </li>
                                    @if($order->discount_amount > 0)
                                        <li style="display: flex; justify-content: space-between; color: #28a745;">
                                            <span>Discount:</span>
                                            <span>-{{ number_format($order->discount_amount, 0, ',', '.') }} ₫</span>
                                        </li>
                                    @endif
                                    <li style="display: flex; justify-content: space-between; border-top: 2px solid #ddd; padding-top: 10px; margin-top: 10px; font-size: 18px; font-weight: bold;">
                                        <span>Total:</span>
                                        <span style="color: #F7941D;">{{ number_format($order->total, 0, ',', '.') }} ₫</span>
                                    </li>
                                </ul>

                                <div style="margin-top: 20px; padding-top: 20px; border-top: 1px solid #ddd;">
                                    <p><strong>Payment Method:</strong></p>
                                    <p>{{ $paymentMethods[$order->payment_method] ?? strtoupper($order->payment_method) }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
