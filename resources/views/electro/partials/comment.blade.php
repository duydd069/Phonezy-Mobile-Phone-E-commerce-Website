<div class="comment-item" data-comment-id="{{ $comment->id }}">
	<div class="comment-header">
		<span class="comment-author">
			<i class="fa fa-user"></i> {{ $comment->user ? $comment->user->name : 'Khách' }}
			@if($comment->user && $comment->user->role_id == 1)
				<span class="admin-badge">Admin</span>
			@endif
		</span>
		<span class="comment-time">
			<i class="fa fa-clock-o"></i> {{ $comment->created_at->diffForHumans() }}
		</span>
	</div>

	<div class="comment-content">
		{{-- Show who is being replied to if this is a reply --}}
		@if($comment->parent_id && $comment->parent && $comment->parent->user)
			<div class="reply-to-info">
				<i class="fa fa-reply"></i> Trả lời 
				<strong>{{ $comment->parent->user->name }}</strong>
			</div>
		@endif
		{{ $comment->content }}
	</div>
	<div class="comment-actions">
		@auth
			{{-- Only show reply button for root comments (level 0) --}}
			@if(!isset($level) || $level == 0)
				<button class="reply-btn" data-parent-id="{{ $comment->id }}">
					<i class="fa fa-reply"></i> Trả lời
				</button>
			@endif
		@endauth
	</div>
	
	@auth
		{{-- Only show reply form for root comments --}}
		@if(!isset($level) || $level == 0)
			<div id="replyForm-{{ $comment->id }}" class="reply-form">
				<form data-parent-id="{{ $comment->id }}">
					@csrf
					<textarea name="content" placeholder="Viết phản hồi của bạn..." required></textarea>
					<div class="btn-group">
						<button type="submit" class="btn btn-primary btn-sm">
							<i class="fa fa-paper-plane"></i> Gửi phản hồi
						</button>
						<button type="button" class="btn btn-secondary btn-sm" onclick="this.closest('.reply-form').classList.remove('active')">
							Hủy
						</button>
					</div>
				</form>
			</div>
		@endif
	@endauth
	
	@if($comment->replies->count() > 0)
		<div class="comment-replies">
			@foreach($comment->replies as $reply)
				@include('electro.partials.comment', ['comment' => $reply, 'level' => isset($level) ? $level + 1 : 1])
			@endforeach
		</div>
	@endif
</div>

