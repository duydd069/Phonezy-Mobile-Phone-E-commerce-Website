@extends('layouts.app')

@section('title', 'Quản lý bình luận - ' . $product->name)

@section('content')
<div class="content-header">
    <div class="container-fluid">
        <div class="row mb-2">
            <div class="col-sm-6">
                <h1 class="m-0">Quản lý bình luận</h1>
            </div>
        </div>
    </div>
</div>

<section class="content">
    <div class="container-fluid">
        <!-- Back button -->
        <div class="mb-3">
            <a href="{{ route('admin.comments.index') }}" class="btn btn-secondary">
                <i class="fas fa-arrow-left"></i> Quay lại danh sách
            </a>
        </div>

        <!-- Product info -->
        <div class="card">
            <div class="card-header bg-primary">
                <h3 class="card-title">Sản phẩm: {{ $product->name }}</h3>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-2">
                        @if($product->image)
                            <img src="{{ preg_match('/^https?:\/\//', $product->image) ? $product->image : asset('storage/' . $product->image) }}" 
                                 alt="{{ $product->name }}" 
                                 class="img-thumbnail">
                        @endif
                    </div>
                    <div class="col-md-10">
                        <p><strong>Danh mục:</strong> {{ $product->category->name ?? 'N/A' }}</p>
                        <p><strong>Tổng bình luận:</strong> 
                            <span >{{ $product->comments->count() }}</span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Success message -->
        @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show">
            <button type="button" class="close" data-dismiss="alert">&times;</button>
            <i class="icon fas fa-check"></i> {{ session('success') }}
        </div>
        @endif

        <!-- Comments list -->
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Danh sách bình luận ({{ $product->comments->count() }})</h3>
            </div>
            <div class="card-body">
                @if($product->comments->count() > 0)
                    @foreach($product->comments as $comment)
                        @include('admin.comments.partials.comment-item', ['comment' => $comment, 'level' => 0])
                    @endforeach
                @else
                    <div class="alert alert-info">
                        <i class="icon fas fa-info"></i>
                        Chưa có bình luận nào cho sản phẩm này.
                    </div>
                @endif
            </div>
        </div>
    </div>
</section>

@push('scripts')
<script>
$(document).ready(function() {
    // Toggle reply form
    $('.btn-reply').on('click', function() {
        const commentId = $(this).data('comment-id');
        $('#replyForm-' + commentId).slideToggle();
    });

    // Delete comment
    $('.btn-delete-comment').on('click', function(e) {
        e.preventDefault();
        
        if (!confirm('Bạn có chắc chắn muốn xóa bình luận này? Tất cả phản hồi cũng sẽ bị xóa.')) {
            return;
        }

        const commentId = $(this).data('comment-id');
        const url = $(this).data('url');
        const commentElement = $(this).closest('.comment-item');

        $.ajax({
            url: url,
            type: 'DELETE',
            data: {
                _token: '{{ csrf_token() }}'
            },
            success: function(response) {
                if (response.success) {
                    commentElement.fadeOut(300, function() {
                        $(this).remove();
                        // Reload page to update counter
                        location.reload();
                    });
                } else {
                    alert(response.message || 'Có lỗi xảy ra');
                }
            },
            error: function(xhr) {
                alert('Có lỗi xảy ra khi xóa bình luận');
            }
        });
    });
});
</script>
@endpush
@endsection
