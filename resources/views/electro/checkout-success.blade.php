@extends('electro.layout')

@section('title', 'Electro - Đặt hàng thành công')

@section('content')
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="text-center" style="padding: 60px 0;">
					@if(session('success'))
						<div class="alert alert-success" style="max-width: 600px; margin: 0 auto 30px;">
							<h4 style="color: #28a745; margin-bottom: 15px;">
								<i class="fa fa-check-circle"></i> {{ session('success') }}
							</h4>
						</div>
					@endif

					@if(session('error'))
						<div class="alert alert-danger" style="max-width: 600px; margin: 0 auto 30px;">
							<h4 style="color: #dc3545; margin-bottom: 15px;">
								<i class="fa fa-exclamation-circle"></i> {{ session('error') }}
							</h4>
						</div>
					@endif

					@if($order)
						<div style="max-width: 800px; margin: 0 auto; background: #f8f9fa; padding: 30px; border-radius: 8px;">
							<h2 style="color: #D10024; margin-bottom: 30px;">Đơn hàng của bạn</h2>

							<div class="order-info" style="text-align: left; margin-bottom: 30px;">
								<p><strong>Mã đơn hàng:</strong> #{{ $order->id }}</p>
								<p><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
								<p><strong>Email:</strong> {{ $order->email }}</p>
								<p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
								<p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
								<p><strong>Tổng tiền:</strong> <span style="color: #D10024; font-size: 18px; font-weight: bold;">{{ number_format($order->total_price, 0, ',', '.') }} ₫</span></p>
								<p><strong>Trạng thái:</strong>
									@if($order->status == 'pending')
										<span class="badge badge-warning">Chờ xử lý</span>
									@elseif($order->status == 'processing')
										<span class="badge badge-info">Đang xử lý</span>
									@elseif($order->status == 'completed')
										<span class="badge badge-success">Hoàn thành</span>
									@else
										<span class="badge badge-danger">Đã hủy</span>
									@endif
								</p>

								@if(!$order->isEmailVerified() && !auth()->check())
									<div class="alert alert-warning" style="margin-top: 20px;">
										<p><strong>Lưu ý:</strong> Vui lòng kiểm tra email <strong>{{ $order->email }}</strong> và click vào link xác nhận để hoàn tất đơn hàng.</p>
									</div>
								@endif
							</div>

							<div class="order-items" style="margin-top: 30px;">
								<h3 style="margin-bottom: 20px;">Chi tiết đơn hàng</h3>
								<table class="table" style="background: white;">
									<thead>
										<tr>
											<th>Sản phẩm</th>
											<th>Số lượng</th>
											<th>Giá</th>
											<th>Thành tiền</th>
										</tr>
									</thead>
									<tbody>
										@foreach($order->items as $item)
											@php
												$product = $item->product ?? null;
											@endphp
											<tr>
												<td>{{ $product->name ?? 'Sản phẩm' }}</td>
												<td>{{ $item->quantity }}</td>
												<td>{{ number_format($item->price_each, 0, ',', '.') }} ₫</td>
												<td>{{ number_format($item->price_each * $item->quantity, 0, ',', '.') }} ₫</td>
											</tr>
										@endforeach
									</tbody>
									<tfoot>
										<tr>
											<td colspan="3" style="text-align: right;"><strong>Tổng cộng:</strong></td>
											<td><strong>{{ number_format($order->total_price, 0, ',', '.') }} ₫</strong></td>
										</tr>
									</tfoot>
								</table>
							</div>
						</div>
					@endif

					<div style="margin-top: 40px;">
						<a href="{{ route('client.index') }}" class="primary-btn">
							Tiếp tục mua sắm
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection

