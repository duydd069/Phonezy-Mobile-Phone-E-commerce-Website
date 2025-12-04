@extends('layouts.app')

@section('content')
<div class="container">
    <h1 class="mb-3">Danh sách sản phẩm</h1>

    {{-- Thanh tìm kiếm + nút thêm sản phẩm --}}
    <div class="d-flex justify-content-between align-items-center mb-3">
        <form class="d-flex" method="GET" action="{{ route('admin.products.index') }}">
            <input type="text" name="q" class="form-control me-2" placeholder="Tìm theo tên/slug..." value="{{ $q ?? '' }}">
            <button class="btn btn-outline-primary">Tìm</button>
        </form>

        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">+ Thêm sản phẩm</a>
    </div>

    {{-- Bảng danh sách sản phẩm --}}
    <div class="table-responsive">
        <table class="table table-bordered align-middle">
            <thead class="table-light">
                <tr>
                    <th width="60">ID</th>
                    <th width="100">Ảnh</th>
                    <th>Tên / Slug</th>
                    <th>Danh mục</th>
                    <th>Thương hiệu</th>
                    <th class="text-end">Giá (VND)</th>
                    <th>Views</th>
                    <th width="160">Thao tác</th>
                </tr>
            </thead>
            <tbody>
                @forelse($products as $p)
                    @php
                        // Kiểm tra nếu image là URL ngoài thì dùng trực tiếp, còn nếu là file nội bộ thì lấy qua storage
                        $isUrl = $p->image && filter_var($p->image, FILTER_VALIDATE_URL);
                        $imgSrc = $p->image
                            ? ($isUrl ? $p->image : asset('storage/'.$p->image))
                            : null;
                    @endphp
                    <tr>
                        <td>{{ $p->id }}</td>
                        <td class="text-center">
                            @if($imgSrc)
                                <img src="{{ $imgSrc }}" alt="{{ $p->name }}"
                                     class="img-thumbnail"
                                     style="width:60px;height:60px;object-fit:cover;">
                            @else
                                <span class="text-muted small">Không có ảnh</span>
                            @endif
                        </td>
                        <td>
                            <div class="fw-semibold">{{ $p->name }}</div>
                            <div class="text-muted small">{{ $p->slug }}</div>
                        </td>
                        <td>{{ $p->category->name ?? '-' }}</td>
                        <td>{{ $p->brand->name ?? '-' }}</td>
                        <td class="text-end">{{ number_format($p->price, 0, ',', '.') }}</td>
                        <td>{{ $p->views }}</td>
                        <td>
                            <a href="{{ route('admin.products.show', $p->id) }}" class="btn btn-sm btn-info">Xem</a>
                            <a href="{{ route('admin.products.edit', $p->id) }}" class="btn btn-sm btn-warning">Sửa</a>

                            <form action="{{ route('admin.products.destroy', $p->id) }}" method="POST"
                                  class="d-inline" onsubmit="return confirm('Bạn có chắc muốn xoá sản phẩm này?');">
                                @csrf
                                @method('DELETE')
                                <button type="submit" class="btn btn-sm btn-danger">Xoá</button>
                            </form>
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="8" class="text-center text-muted py-3">Chưa có sản phẩm nào.</td>
                    </tr>
                @endforelse
            </tbody>
        </table>
    </div>

    {{-- Phân trang --}}
    <div class="mt-3">
        {{ $products->links() }}
    </div>
</div>
@endsection
