@extends('electro.layout')

@section('title', 'Electro - Product')

@section('content')
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-md-5">
				<div id="product-main-img">
					<div class="product-preview">
						<img src="{{ $product->image ? (preg_match('/^https?:\\/\\//', $product->image) ? $product->image : asset('storage/' . $product->image)) : asset('electro/img/product01.png') }}" alt="{{ $product->name }}">
					</div>
				</div>
			</div>
			<div class="col-md-7">
				<div class="product-details">
					<h2 class="product-name">{{ $product->name }}</h2>
					<div>
						<div class="product-rating">
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
							<i class="fa fa-star"></i>
						</div>
					</div>
					<div>
						<h3 class="product-price">{{ number_format($product->price, 0, ',', '.') }} ₫</h3>
					</div>
					<p>{!! nl2br(e($product->description)) !!}</p>
					<ul class="product-links">
						<li>Category:</li>
						<li><a href="#">{{ $product->category->name ?? 'N/A' }}</a></li>
					</ul>
					<ul class="product-links">
						<li>Brand:</li>
						<li><a href="#">{{ $product->brand->name ?? 'N/A' }}</a></li>
					</ul>
					<div class="add-to-cart">
						<button class="add-to-cart-btn"><i class="fa fa-shopping-cart"></i> add to cart</button>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>
@endsection


