@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Quản lý màu sắc</h3>
    <a href="{{ route('admin.colors.create') }}" class="btn btn-primary">+ Thêm màu sắc</a>
  </div>

  {{-- Bảng dữ liệu --}}
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th width="60">ID</th>
          <th>Tên màu</th>
          <th>Mã màu (Hex)</th>
          <th>Số biến thể sử dụng</th>
          <th width="200">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($colors as $color)
          <tr>
            <td>{{ $color->id }}</td>
            <td>
              <span class="badge" style="background-color: {{ $color->hex_code ?? '#6c757d' }}; color: white; padding: 5px 10px;">
                {{ $color->name }}
              </span>
            </td>
            <td>
              @if($color->hex_code)
                <code>{{ $color->hex_code }}</code>
              @else
                <span class="text-muted">—</span>
              @endif
            </td>
            <td>{{ $color->variants()->count() }}</td>
            <td class="d-flex gap-2">
              <a href="{{ route('admin.colors.edit', $color) }}" class="btn btn-sm btn-warning">Sửa</a>
              <form action="{{ route('admin.colors.destroy', $color) }}" method="post" onsubmit="return confirm('Bạn có chắc muốn xóa màu này?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" type="submit">Xóa</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="5" class="text-center text-muted py-3">Chưa có màu sắc nào.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Phân trang --}}
  {{ $colors->links() }}
</div>
@endsection

