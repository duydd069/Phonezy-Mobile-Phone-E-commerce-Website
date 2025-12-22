@php
    $replyParentId = $level === 0 ? $comment->id : $comment->parent_id;
    $replyToUserId = $comment->user_id;
    $replyToName = $comment->user ? $comment->user->name : 'Khách';
@endphp

<div class="comment-item {{ $level > 0 ? 'ml-5 border-left border-primary pl-3' : '' }} mb-3 p-3 border rounded" style="background: {{ $level > 0 ? '#f8f9fa' : '#ffffff' }};">
    <div class="d-flex justify-content-between align-items-start mb-2">
        <div>
            <strong>
                <i class="fas fa-user"></i> {{ $comment->user ? $comment->user->name : 'Khách' }}
                @if($comment->user && ($comment->user->role_id == 1 || ($comment->user->roles && $comment->user->roles->contains('name', 'admin'))))
                    <span class="badge bg-danger text-white">ADMIN</span>
                @endif
            </strong>
            <br>
            <small class="text-muted">
                <i class="far fa-clock"></i> {{ $comment->created_at->format('d/m/Y H:i') }} 
                ({{ $comment->created_at->diffForHumans() }})
            </small>
        </div>
        <div>
            <button type="button" 
                    class="btn btn-sm btn-primary btn-reply" 
                    data-comment-id="{{ $comment->id }}">
                <i class="fas fa-reply"></i> Trả lời
            </button>
            <button type="button"
                    class="btn btn-sm btn-danger btn-delete-comment"
                    data-comment-id="{{ $comment->id }}"
                    data-url="{{ route('admin.comments.destroy', $comment->id) }}">
                <i class="fas fa-trash"></i> Xóa
            </button>
        </div>
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

    <!-- Reply form -->
    <div id="replyForm-{{ $comment->id }}" class="mt-3" style="display: none;">
        <form action="{{ route('admin.comments.reply') }}" method="POST" class="border-top pt-3">
            @csrf
            <input type="hidden" name="product_id" value="{{ $product->id }}">
            <input type="hidden" name="parent_id" value="{{ $replyParentId }}">
            <input type="hidden" name="replied_to_user_id" value="{{ $replyToUserId }}">
            
            <div class="form-group">
                <label>Trả lời bình luận của <strong>{{ $replyToName }}</strong>:</label>
                <textarea name="content"
                    class="form-control"
                    rows="3"
                    placeholder="Nhập nội dung trả lời..."
                    required></textarea>
            </div>
            
            <div class="form-group mb-0">
                <button type="submit" class="btn btn-success btn-sm">
                    <i class="fas fa-paper-plane"></i> Gửi trả lời
                </button>
                <button type="button" 
                        class="btn btn-secondary btn-sm" 
                        onclick="$('#replyForm-{{ $comment->id }}').slideUp();">
                    Hủy
                </button>
            </div>
        </form>
    </div>

    <!-- Child replies -->
    @if($level === 0 && $comment->replies->count() > 0)
        <div class="mt-3">
            @foreach($comment->replies as $reply)
                @include('admin.comments.partials.comment-item', ['comment' => $reply, 'level' => 1, 'product' => $product])
            @endforeach
        </div>
    @endif
</div>
