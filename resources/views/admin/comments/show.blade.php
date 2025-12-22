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
                            <img src="{{ preg_match('/^https?:\/\//', $product->image)
                                ? $product->image
                                : asset('storage/' . $product->image) }}"
                                alt="{{ $product->name }}"
                                class="img-thumbnail">
                        @endif
                    </div>
                    <div class="col-md-10">
                        <p><strong>Danh mục:</strong> {{ $product->category->name ?? 'N/A' }}</p>
                        <p>
                            <strong>Tổng bình luận:</strong>
                            {{ $product->comments->count() }}
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
                <h3 class="card-title">
                    Danh sách bình luận ({{ $product->comments->count() }})
                </h3>
            </div>
            <div class="card-body">

                @if($product->comments->count() > 0)
                    @foreach($product->comments as $comment)
                        @include('admin.comments.partials.comment-item', [
                            'comment' => $comment,
                            'level'   => 0,
                            'product' => $product
                        ])
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
@endsection

@push('scripts')
<script>
$(document).ready(function() {
    
    // Toggle reply form
    $(document).off('click', '.btn-reply').on('click', '.btn-reply', function (e) {
      e.preventDefault();

      const commentId = $(this).data('comment-id');
      $('#replyForm-' + commentId).slideToggle();
  });

    // Delete comment
   $(document).on('click', '.btn-delete-comment', function (e) {
    e.preventDefault();
    e.stopImmediatePropagation();
//    e.stopPropagation();
    
    console.log('CLICK OK'); // debug

    const ok = confirm('Bạn có chắc chắn muốn xóa bình luận này? Tất cả phản hồi cũng sẽ bị xóa.');
    if (!ok) return;

    const url = $(this).data('url');
    const commentElement = $(this).closest('.comment-item');

    $.ajax({
        url: url,
        type: 'DELETE',
        data: {
            _token: '{{ csrf_token() }}'
        },
        success: function (res) {
            alert('XÓA OK');
            commentElement.fadeOut(300, function () {
                $(this).remove();
            });
        },
        error: function (xhr) {
            console.log(xhr.responseText);
            alert('Xóa lỗi');
        }
    });
  });

document.addEventListener('DOMContentLoaded', function () {
    document.querySelectorAll('.btn-reply').forEach(function (btn) {
        btn.addEventListener('click', function () {
            const commentId = this.dataset.commentId;
            toggleReply(commentId);
        });

    });
    document.querySelectorAll('.btn-delete-comment').forEach(function (btn) {
        btn.addEventListener('click', function (e) {
            e.preventDefault();

            if (!confirm('Bạn có chắc chắn muốn xóa bình luận này? Tất cả phản hồi cũng sẽ bị xóa.')) {
                return;
            }

            const url = this.dataset.url;
            const commentElement = this.closest('.comment-item');

            fetch(url, {
                method: 'DELETE',
                headers: {
                    'X-CSRF-TOKEN': '{{ csrf_token() }}',
                    'Accept': 'application/json'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    commentElement.remove();
                    location.reload();
                } else {
                    alert(data.message || 'Có lỗi xảy ra');
                }
            })
            .catch(() => {
                alert('Có lỗi xảy ra khi xóa bình luận');
            });
        });
    });

});

});
function toggleReply(commentId) {
    const form = document.getElementById('replyForm-' + commentId);
    if (!form) return;

    form.style.display =
        (form.style.display === 'none' || form.style.display === '')
            ? 'block'
            : 'none';
}
</script>
@endpush
