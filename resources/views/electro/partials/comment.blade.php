<div class="comment-item" data-comment-id="{{ $comment->id }}">
	<div class="comment-header">
		<span class="comment-author">
			<i class="fa fa-user"></i> {{ $comment->user ? $comment->user->name : 'Khách' }}
		</span>
		<span class="comment-time">
			<i class="fa fa-clock-o"></i> {{ $comment->created_at->diffForHumans() }}
		</span>
	</div>
	<div class="comment-content">
		{{ $comment->content }}
	</div>
	<div class="comment-actions">
		@auth
			<button class="reply-btn" data-parent-id="{{ $comment->id }}">
				<i class="fa fa-reply"></i> Trả lời
			</button>
		@endauth
	</div>
	
	@auth
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
	@endauth
	
	@if($comment->replies->count() > 0)
		<div class="comment-replies">
			@foreach($comment->replies as $reply)
				@include('electro.partials.comment', ['comment' => $reply, 'level' => $level + 1])
			@endforeach
		</div>
	@endif
</div>

