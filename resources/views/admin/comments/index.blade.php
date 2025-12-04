@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
    <div class="d-flex justify-content-between align-items-center mb-3">
        <h3 class="m-0">Quản lý bình luận</h3>
    </div>

    <div class="card">
        <div class="card-body">
            <h5 class="card-title mb-3">Sản phẩm có bình luận</h5>
            
            <div class="table-responsive">
                <table class="table table-striped align-middle">
                    <thead>
                        <tr>
                            <th width="80">Ảnh</th>
                            <th>Tên sản phẩm</th>
                            <th width="150" class="text-center">Số bình luận</th>
                            <th width="150" class="text-center">Thao tác</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>
                                    @if($product->image)
                                        <img src="{{ preg_match('/^https?:\/\//', $product->image) ? $product->image : asset('storage/' . $product->image) }}" 
                                             alt="{{ $product->name }}" 
                                             style="width: 60px; height: 60px; object-fit: cover; border-radius: 4px;">
                                    @else
                                        <div style="width: 60px; height: 60px; background: #f0f0f0; border-radius: 4px; display: flex; align-items: center; justify-content: center;">
                                            <i class="fa fa-image text-muted"></i>
                                        </div>
                                    @endif
                                </td>
                                <td>
                                    <div class="fw-semibold">{{ $product->name }}</div>
                                    <small class="text-muted">{{ $product->slug }}</small>
                                </td>
                                <td class="text-center">
                                    <span class="badge bg-primary" style="font-size: 14px;">
                                        {{ $product->comments_count }} bình luận
                                    </span>
                                </td>
                                <td class="text-center">
                                    <a href="{{ route('admin.comments.show', $product) }}" class="btn btn-sm btn-primary">
                                        <i class="fa fa-eye"></i> Xem bình luận
                                    </a>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="text-center text-muted py-4">
                                    Chưa có sản phẩm nào có bình luận
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>

            {{ $products->links() }}
        </div>
    </div>
</div>
@endsection
