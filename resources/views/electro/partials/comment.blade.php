@php
	// Determine parent ID for replies based on nesting level
	$replyParentId = $level === 0 ? $comment->id : $comment->parent_id;
	$replyToUserId = $comment->user_id;
	$replyToName = $comment->user ? $comment->user->name : 'Khách';
@endphp

<div class="comment-item {{ $level > 0 ? 'comment-reply' : '' }}" data-comment-id="{{ $comment->id }}">
	<div class="comment-header">
		<span class="comment-author">
			<i class="fa fa-user"></i> {{ $comment->user ? $comment->user->name : 'Khách' }}
			@if($comment->user && ($comment->user->role_id == 1 || ($comment->user->roles && $comment->user->roles->contains('name', 'admin'))))
				<span class="admin-badge">ADMIN</span>
			@endif
		</span>
		<span class="comment-time">
			<i class="fa fa-clock-o"></i> {{ $comment->created_at->diffForHumans() }}
		</span>
	</div>
	<div class="comment-content">
		@if($comment->replied_to_user_id && $comment->repliedToUser)
			<span class="reply-to">
		@endif
		{{ $comment->content }}
	</div>
	<div class="comment-actions">
		@auth
			<button class="reply-btn" 
				data-parent-id="{{ $replyParentId }}"
				data-replied-to-user-id="{{ $replyToUserId }}"
				data-replied-to-name="{{ $replyToName }}">
				<i class="fa fa-reply"></i> Trả lời
			</button>
		@endauth
	</div>
	
	@auth
		<div id="replyForm-{{ $comment->id }}" class="reply-form">
			<form data-parent-id="{{ $replyParentId }}" data-replied-to-user-id="{{ $replyToUserId }}">
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
	
	@if($level === 0 && $comment->replies->count() > 0)
		<div class="comment-replies">
			@foreach($comment->replies as $reply)
				@include('electro.partials.comment', ['comment' => $reply, 'level' => 1])
			@endforeach
		</div>
	@endif
</div>
