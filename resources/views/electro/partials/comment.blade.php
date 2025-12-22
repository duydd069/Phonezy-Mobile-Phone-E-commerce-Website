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
			@if ($comment->replied_to_user_id && $comment->repliedToUser)
				@if (!\Illuminate\Support\Str::startsWith($comment->content, '@'))
					<span class="reply-to">
						{{ '@' . $comment->repliedToUser->name }}
					</span>
				@endif
			@endif

			{{ $comment->content }}
		</div>
	<div class="comment-content">
		@if($comment->replied_to_user_id && $comment->repliedToUser)
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

	<div class="comment_item">
		@if(auth()->check() && (
			auth()->user()->role_id == 1 || auth()->id() === $comment->user_id))
		<div class="comment-actions mt-2">
			<button type="button"
				class="btn-delete-comment delete-btn"
				data-url="{{ route('client.comments.destroy', $comment->id) }}">
				<i class="fas fa-trash"></i>
				<span>Xóa</span>
			</button>
		</div>
		@endif
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

@push('scripts')
<script>
$(document).on('click', '.btn-delete-comment', function (e) {
    e.preventDefault();
	e.stopImmediatePropagation();
    if (!confirm('Bạn có chắc muốn xóa bình luận này?')) return;

    const url = $(this).data('url');
    const commentItem = $(this).closest('.comment-item');

    console.log('DELETE URL:', url);

    $.ajax({
        url: url,
        method: 'POST',
        data: {
            _token: '{{ csrf_token() }}',
            _method: 'DELETE'
        },
        success: function (res) {
            console.log('SUCCESS:', res);
            commentItem.fadeOut(300, function () {
                $(this).remove();
            });
        },
        error: function (xhr) {
            console.log('STATUS:', xhr.status);
            console.log('RESPONSE:', xhr.responseText);
            alert('Xóa thất bại');
        }
    });
});
</script>
@endpush
<style>
	.delete-btn {
    background: transparent;
    border: 1px solid #f5c2c7;
    color: #dc3545;
    font-size: 13px;
    padding: 4px 10px;
    border-radius: 6px;
    display: inline-flex;
    align-items: center;
    gap: 6px;
    cursor: pointer;
    transition: all 0.2s ease;
}

.delete-btn i {
    font-size: 12px;
}

.delete-btn:hover {
    background: #dc3545;
    color: #fff;
    border-color: #dc3545;
}

.delete-btn:active {
    transform: scale(0.96);
}

</style>

