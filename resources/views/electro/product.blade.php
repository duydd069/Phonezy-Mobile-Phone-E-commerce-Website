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

<!-- Comments Section -->
<div class="section">
	<div class="container">
		<div class="row">
			<div class="col-md-12">
				<div class="section-title">
					<h3 class="title">Bình luận ({{ $product->comments->count() }})</h3>
				</div>

				<!-- Comment Form -->
				@auth
					<div class="comment-form-wrapper" style="margin-bottom: 40px;">
						<form id="commentForm" class="comment-form">
							@csrf
							<div class="form-group">
								<textarea 
									name="content" 
									id="commentContent" 
									class="form-control" 
									rows="4" 
									placeholder="Viết bình luận của bạn..." 
									required
									style="resize: vertical; min-height: 100px;"
								></textarea>
							</div>
							<button type="submit" class="btn btn-primary">
								<i class="fa fa-paper-plane"></i> Gửi bình luận
							</button>
						</form>
					</div>
				@else
					<div class="alert alert-info" style="margin-bottom: 40px;">
						<p>Vui lòng <a href="{{ route('client.login') }}" style="color: #D10024; font-weight: bold;">đăng nhập</a> để bình luận.</p>
					</div>
				@endauth

				<!-- Comments List -->
				<div id="commentsList" class="comments-list">
					@if($product->comments->count() > 0)
						@foreach($product->comments as $comment)
							@include('electro.partials.comment', ['comment' => $comment, 'level' => 0])
						@endforeach
					@else
						<p class="text-muted">Chưa có bình luận nào. Hãy là người đầu tiên bình luận!</p>
					@endif
				</div>
			</div>
		</div>
	</div>
</div>

@push('styles')
<style>
	.comment-form-wrapper {
		background: #f8f9fa;
		padding: 20px;
		border-radius: 8px;
		margin-bottom: 30px;
	}
	
	.comment-item {
		background: #fff;
		border: 1px solid #e4e7ed;
		border-radius: 8px;
		padding: 15px;
		margin-bottom: 15px;
		transition: box-shadow 0.3s;
	}
	
	.comment-item:hover {
		box-shadow: 0 2px 8px rgba(0,0,0,0.1);
	}
	
	.comment-header {
		display: flex;
		justify-content: space-between;
		align-items: center;
		margin-bottom: 10px;
	}
	
	.comment-author {
		font-weight: 600;
		color: #2B2D42;
		font-size: 14px;
	}
	
	.comment-time {
		color: #8D99AE;
		font-size: 12px;
	}
	
	.comment-content {
		color: #434343;
		line-height: 1.6;
		margin-bottom: 10px;
		white-space: pre-wrap;
		word-wrap: break-word;
	}
	
	.comment-actions {
		display: flex;
		gap: 15px;
	}
	
	.reply-btn {
		background: none;
		border: none;
		color: #D10024;
		cursor: pointer;
		font-size: 13px;
		padding: 5px 0;
		transition: color 0.3s;
	}
	
	.reply-btn:hover {
		color: #B8001F;
		text-decoration: underline;
	}
	
	.reply-form {
		margin-top: 15px;
		padding-top: 15px;
		border-top: 1px solid #e4e7ed;
		display: none;
	}
	
	.reply-form.active {
		display: block;
	}
	
	.reply-form textarea {
		width: 100%;
		padding: 10px;
		border: 1px solid #e4e7ed;
		border-radius: 4px;
		resize: vertical;
		min-height: 80px;
		margin-bottom: 10px;
	}
	
	.reply-form .btn-group {
		display: flex;
		gap: 10px;
	}
	
	.comment-replies {
		margin-top: 15px;
		margin-left: 40px;
		padding-left: 20px;
		border-left: 2px solid #e4e7ed;
	}
	
	.comment-replies .comment-item {
		background: #f8f9fa;
	}
	
	.btn-primary {
		background-color: #D10024;
		border-color: #D10024;
		color: #fff;
		padding: 8px 20px;
		border-radius: 4px;
		transition: background-color 0.3s;
	}
	
	.btn-primary:hover {
		background-color: #B8001F;
		border-color: #B8001F;
	}
	
	.btn-secondary {
		background-color: #8D99AE;
		border-color: #8D99AE;
		color: #fff;
		padding: 8px 20px;
		border-radius: 4px;
	}
	
	.alert-info {
		background-color: #E3F2FD;
		border-color: #BBDEFB;
		color: #1976D2;
		padding: 15px;
		border-radius: 4px;
	}
</style>
@endpush

@push('scripts')
<script>
	document.addEventListener('DOMContentLoaded', function() {
		const commentForm = document.getElementById('commentForm');
		const commentsList = document.getElementById('commentsList');
		const productId = {{ $product->id }};
		const commentUrl = '{{ route("client.comments.store", $product->slug) }}';
		
		// Handle main comment form
		if (commentForm) {
			commentForm.addEventListener('submit', function(e) {
				e.preventDefault();
				submitComment(null);
			});
		}
		
		// Handle reply buttons
		document.querySelectorAll('.reply-btn').forEach(btn => {
			btn.addEventListener('click', function() {
				const parentId = this.dataset.parentId;
				const replyForm = document.getElementById('replyForm-' + parentId);
				if (replyForm) {
					replyForm.classList.toggle('active');
					if (replyForm.classList.contains('active')) {
						replyForm.querySelector('textarea').focus();
					}
				}
			});
		});
		
		// Handle reply form submissions
		document.querySelectorAll('.reply-form form').forEach(form => {
			form.addEventListener('submit', function(e) {
				e.preventDefault();
				const parentId = this.dataset.parentId;
				submitComment(parentId, this);
			});
		});
		
		function submitComment(parentId, formElement = null) {
			const content = formElement 
				? formElement.querySelector('textarea').value 
				: document.getElementById('commentContent').value;
			
			if (!content.trim()) {
				alert('Vui lòng nhập nội dung bình luận');
				return;
			}
			
			const formData = new FormData();
			formData.append('content', content);
			formData.append('_token', '{{ csrf_token() }}');
			if (parentId) {
				formData.append('parent_id', parentId);
			}
			
			fetch(commentUrl, {
				method: 'POST',
				body: formData,
				headers: {
					'X-Requested-With': 'XMLHttpRequest'
				}
			})
			.then(response => response.json())
			.then(data => {
				if (data.success) {
					// Reload page to show new comment
					location.reload();
				} else {
					if (data.message === 'Vui lòng đăng nhập để bình luận') {
						window.location.href = '{{ route("client.login") }}';
					} else {
						alert(data.message || 'Có lỗi xảy ra');
					}
				}
			})
			.catch(error => {
				console.error('Error:', error);
				alert('Có lỗi xảy ra khi gửi bình luận');
			});
		}
	});
</script>
@endpush
@endsection


