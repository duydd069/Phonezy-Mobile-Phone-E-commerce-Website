@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Quản lý thương hiệu</h3>
    <a href="{{ route('admin.brands.create') }}" class="btn btn-primary">+ Thêm thương hiệu</a>
  </div>

  <form method="get" class="row g-2 mb-3">
    <div class="col-auto">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tìm theo tên hoặc slug">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary" type="submit">Tìm kiếm</button>
    </div>
  </form>

  <div class="table-responsive">

    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Logo</th>
          <th>Tên</th>
          <th>Slug</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($brands as $brand)
          <tr>
            <td>{{ $brand->id }}</td>
            <td>
              @if($brand->logo)
                <img src="{{ asset('storage/' . $brand->logo) }}" alt="logo" style="height:32px">
              @else
                <span class="badge text-bg-secondary">Không có ảnh</span>
              @endif
            </td>
            <td>{{ $brand->name }}</td>
            <td>{{ $brand->slug }}</td>
            <td class="d-flex gap-2">
              <a href="{{ route('admin.brands.show', $brand) }}" class="btn btn-sm btn-secondary">Xem</a>
              <a href="{{ route('admin.brands.edit', $brand) }}" class="btn btn-sm btn-warning">Sửa</a>
              <form action="{{ route('admin.brands.destroy', $brand) }}" method="post" onsubmit="return confirm('Bạn có chắc muốn xóa thương hiệu này?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" type="submit">Xóa</button>
              </form>
            </td>
          </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center text-muted py-3">Chưa có thương hiệu nào.</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $brands->links() }}
</div>
@endsection