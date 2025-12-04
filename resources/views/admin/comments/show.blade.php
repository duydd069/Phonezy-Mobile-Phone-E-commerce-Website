@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <div>
            <h3 class="m-0">Bình luận sản phẩm</h3>
            <p class="text-muted mb-0">{{ $product->name }}</p>
        </div>
        <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">
            <i class="fa fa-arrow-left"></i> Quay lại
        </a>
    </div>

    <div class="card">
        <div class="card-body">
            @foreach($comments as $comment)
                <div class="comment-admin-item mb-3">
                    <div class="d-flex justify-content-between align-items-start mb-2">
                        <div>
                            <strong>{{ $comment->user ? $comment->user->name : 'Khách' }}</strong>
                            @if($comment->user && $comment->user->role_id == 1)
                                <span class="badge bg-danger ms-2">Admin</span>
                            @endif
                            <br>
                            <small class="text-muted">{{ $comment->created_at->format('d/m/Y H:i') }}</small>
                        </div>
                        <form action="{{ route('admin.comments.destroy', $comment) }}" method="POST" 
                              onsubmit="return confirm('Bạn có chắc muốn xóa bình luận này và tất cả câu trả lời?')">
                            @csrf
                            @method('DELETE')
                            <button type="submit" class="btn btn-sm btn-danger">
                                <i class="fa fa-trash"></i> Xóa
                            </button>
                        </form>
                    </div>
                    <div class="comment-content p-3 bg-light rounded">
                        {{ $comment->content }}
                    </div>

                    {{-- Admin Reply Form --}}
                    <div class="mt-2">
                        <button class="btn btn-sm btn-outline-primary" type="button" 
                                onclick="document.getElementById('replyForm-{{ $comment->id }}').classList.toggle('d-none')">
                            <i class="fa fa-reply"></i> Trả lời
                        </button>
                    </div>
                    
                    <form id="replyForm-{{ $comment->id }}" class="d-none mt-3 p-3 bg-white border rounded" 
                          action="{{ route('admin.comments.reply', $comment) }}" method="POST">
                        @csrf
                        <textarea name="content" class="form-control mb-2" rows="3" 
                                  placeholder="Nhập câu trả lời..." required></textarea>
                        <div class="d-flex gap-2">
                            <button type="submit" class="btn btn-sm btn-primary">
                                <i class="fa fa-paper-plane"></i> Gửi trả lời
                            </button>
                            <button type="button" class="btn btn-sm btn-secondary" 
                                    onclick="document.getElementById('replyForm-{{ $comment->id }}').classList.add('d-none')">
                                Hủy
                            </button>
                        </div>
                    </form>

                    @if($comment->replies->count() > 0)

                        <div class="ms-4 mt-3">
                            <strong class="text-muted">Câu trả lời ({{ $comment->replies->count() }}):</strong>
                            @foreach($comment->replies as $reply)
                                <div class="reply-admin-item mt-2 p-3 border rounded">
                                    <div class="d-flex justify-content-between align-items-start mb-2">
                                        <div>
                                            <strong>{{ $reply->user ? $reply->user->name : 'Khách' }}</strong>
                                            @if($reply->user && $reply->user->role_id == 1)
                                                <span class="badge bg-danger ms-2">Admin</span>
                                            @endif
                                            <br>
                                            <small class="text-muted">
                                                @if($reply->parent && $reply->parent->user)
                                                    Trả lời {{ $reply->parent->user->name }} • 
                                                @endif
                                                {{ $reply->created_at->format('d/m/Y H:i') }}
                                            </small>
                                        </div>
                                        <form action="{{ route('admin.comments.destroy', $reply) }}" method="POST"
                                              onsubmit="return confirm('Bạn có chắc muốn xóa câu trả lời này?')">
                                            @csrf
                                            @method('DELETE')
                                            <button type="submit" class="btn btn-sm btn-outline-danger">
                                                <i class="fa fa-trash"></i>
                                            </button>
                                        </form>
                                    </div>
                                    <div>{{ $reply->content }}</div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
                <hr>
            @endforeach

            @if($comments->isEmpty())
                <p class="text-center text-muted py-4">Chưa có bình luận nào</p>
            @endif

            {{ $comments->links() }}
        </div>
    </div>
</div>

<style>
    .comment-admin-item {
        padding: 15px;
        background: #fff;
        border: 1px solid #e0e0e0;
        border-radius: 8px;
    }
    
    .reply-admin-item {
        background: #f8f9fa;
    }
</style>
@endsection
