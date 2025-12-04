@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Quản lý danh mục</h3>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">+ Thêm danh mục</a>
  </div>

  {{-- Form tìm kiếm --}}
  <form method="get" class="row g-2 mb-3">
    <div class="col-auto">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tìm theo tên hoặc slug">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary" type="submit">Tìm kiếm</button>
    </div>
  </form>

  {{-- Bảng dữ liệu --}}
  <div class="table-responsive">

    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Tên</th>
          <th>Slug</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $category)
          <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->slug }}</td>
            <td class="d-flex gap-2">
              <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-secondary">Xem</a>
              <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">Sửa</a>
              <form action="{{ route('admin.categories.destroy', $category) }}" method="post" onsubmit="return confirm('Bạn có chắc muốn xóa danh mục này?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" type="submit">Xóa</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="text-center text-muted py-3">Chưa có danh mục nào.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Phân trang --}}
  {{ $categories->links() }}
</div>
@endsection
