<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	<title>Xác nhận đơn hàng</title>
	<style>
		body {
			font-family: Arial, sans-serif;
			line-height: 1.6;
			color: #333;
			max-width: 600px;
			margin: 0 auto;
			padding: 20px;
		}
		.header {
			background-color: #D10024;
			color: white;
			padding: 20px;
			text-align: center;
			border-radius: 5px 5px 0 0;
		}
		.content {
			background-color: #f8f9fa;
			padding: 30px;
			border: 1px solid #ddd;
		}
		.order-info {
			background-color: white;
			padding: 20px;
			margin: 20px 0;
			border-radius: 5px;
		}
		.order-info p {
			margin: 10px 0;
		}
		.btn {
			display: inline-block;
			padding: 12px 30px;
			background-color: #D10024;
			color: white;
			text-decoration: none;
			border-radius: 5px;
			margin: 20px 0;
		}
		.footer {
			text-align: center;
			margin-top: 30px;
			color: #666;
			font-size: 12px;
		}
		table {
			width: 100%;
			border-collapse: collapse;
			margin: 20px 0;
		}
		table th, table td {
			padding: 10px;
			text-align: left;
			border-bottom: 1px solid #ddd;
		}
		table th {
			background-color: #f8f9fa;
		}
	</style>
</head>
<body>
	<div class="header">
		<h1>Xác nhận đơn hàng #{{ $order->id }}</h1>
	</div>

	<div class="content">
		<p>Xin chào <strong>{{ $order->customer_name }}</strong>,</p>

		<p>Cảm ơn bạn đã đặt hàng tại cửa hàng của chúng tôi!</p>

		<div class="order-info">
			<h3>Thông tin đơn hàng</h3>
			<p><strong>Mã đơn hàng:</strong> #{{ $order->id }}</p>
			<p><strong>Họ tên:</strong> {{ $order->customer_name }}</p>
			<p><strong>Email:</strong> {{ $order->email }}</p>
			<p><strong>Số điện thoại:</strong> {{ $order->customer_phone }}</p>
			<p><strong>Địa chỉ giao hàng:</strong> {{ $order->shipping_address }}</p>
			<p><strong>Tổng tiền:</strong> {{ number_format($order->total_price, 0, ',', '.') }} ₫</p>
		</div>

		<div class="order-info">
			<h3>Chi tiết đơn hàng</h3>
			<table>
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

		<p><strong>Vui lòng click vào nút bên dưới để xác nhận đơn hàng:</strong></p>

		<div style="text-align: center;">
			<a href="{{ $verificationUrl }}" class="btn">Xác nhận đơn hàng</a>
		</div>

		<p style="margin-top: 20px; color: #666; font-size: 14px;">
			<strong>Lưu ý:</strong> Link xác nhận này sẽ hết hạn sau 24 giờ. Nếu bạn không click vào link, đơn hàng sẽ không được xử lý.
		</p>

		<p style="margin-top: 20px;">
			Nếu bạn không thực hiện đặt hàng này, vui lòng bỏ qua email này.
		</p>
	</div>

	<div class="footer">
		<p>© {{ date('Y') }} Phonezy. Tất cả quyền được bảo lưu.</p>
		<p>Email này được gửi tự động, vui lòng không trả lời email này.</p>
	</div>
</body>
</html>

