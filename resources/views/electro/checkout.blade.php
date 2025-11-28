@extends('electro.layout')

@section('title', 'Electro - Thanh toán')

@section('content')
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="section-title">
					<h3 class="title">Thông tin thanh toán</h3>
				</div>
			</div>
		</div>

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
			{{-- Form thông tin khách hàng --}}
			<div class="col-md-8">
				<form action="{{ route('client.checkout.store') }}" method="POST">
					@csrf

					<div class="billing-details">
						<div class="form-group">
							<label>Họ và tên <span class="text-danger">*</span></label>
							<input class="input"
								   type="text"
								   name="customer_name"
								   value="{{ old('customer_name', auth()->check() ? auth()->user()->name : '') }}"
								   required>
						</div>

						<div class="form-group">
							<label>Email <span class="text-danger">*</span></label>
							<input class="input"
								   type="email"
								   name="email"
								   value="{{ old('email', auth()->check() ? auth()->user()->email : '') }}"
								   required>
							@if(!auth()->check())
								<small class="form-text text-muted">
									Chúng tôi sẽ gửi email xác nhận đơn hàng đến địa chỉ này.
								</small>
							@endif
						</div>

						<div class="form-group">
							<label>Số điện thoại <span class="text-danger">*</span></label>
							<input class="input"
								   type="text"
								   name="customer_phone"
								   value="{{ old('customer_phone') }}"
								   required>
						</div>

						<div class="form-group">
							<label>Địa chỉ giao hàng <span class="text-danger">*</span></label>
							<textarea class="input"
									  name="shipping_address"
									  rows="3"
									  required>{{ old('shipping_address') }}</textarea>
						</div>

						<div class="form-group">
							<label>Ghi chú (tùy chọn)</label>
							<textarea class="input"
									  name="notes"
									  rows="3">{{ old('notes') }}</textarea>
						</div>
					</div>

					<button type="submit" class="primary-btn order-submit">
						Đặt hàng
					</button>
				</form>
			</div>

			{{-- Tóm tắt đơn hàng --}}
			<div class="col-md-4">
				<div class="order-details">
					<div class="section-title text-center">
						<h3 class="title">Tóm tắt đơn hàng</h3>
					</div>

					<div class="order-summary">
						<div class="order-col">
							<div><strong>SẢN PHẨM</strong></div>
							<div><strong>TỔNG</strong></div>
						</div>

						<div class="order-products">
							@foreach($items as $item)
								@php
									$product = $item->product;
									$price   = $product->price ?? 0;
								@endphp
								<div class="order-col">
									<div>{{ $item->quantity }}x {{ $product->name ?? 'Sản phẩm' }}</div>
									<div>{{ number_format($price * $item->quantity, 0, ',', '.') }} ₫</div>
								</div>
							@endforeach
						</div>

						<div class="order-col">
							<div><strong>TỔNG CỘNG</strong></div>
							<div><strong class="order-total">
									{{ number_format($total, 0, ',', '.') }} ₫
								</strong>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection
