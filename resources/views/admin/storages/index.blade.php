@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Quản lý dung lượng</h3>
    <a href="{{ route('admin.storages.create') }}" class="btn btn-primary">+ Thêm dung lượng</a>
  </div>

  {{-- Thông báo --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  {{-- Bảng dữ liệu --}}
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th width="60">ID</th>
          <th>Dung lượng</th>
          <th>Số biến thể sử dụng</th>
          <th width="200">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($storages as $storage)
          <tr>
            <td>{{ $storage->id }}</td>
            <td><span class="badge bg-secondary">{{ $storage->storage }}</span></td>
            <td>{{ $storage->variants()->count() }}</td>
            <td class="d-flex gap-2">
              <a href="{{ route('admin.storages.edit', $storage) }}" class="btn btn-sm btn-warning">Sửa</a>
              <form action="{{ route('admin.storages.destroy', $storage) }}" method="post" onsubmit="return confirm('Bạn có chắc muốn xóa dung lượng này?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" type="submit">Xóa</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="text-center text-muted py-3">Chưa có dung lượng nào.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Phân trang --}}
  {{ $storages->links() }}
</div>
@endsection

