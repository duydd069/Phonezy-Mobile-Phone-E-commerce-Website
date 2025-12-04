@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Quản lý người dùng</h3>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">+ Thêm người dùng</a>
  </div>

  <form method="get" class="row g-2 mb-3">
    <div class="col-auto">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tìm theo tên hoặc email">
    </div>
    <div class="col-auto">
      <select name="role_id" class="form-select">
        <option value="">Tất cả vai trò</option>
        <option value="1" {{ request('role_id') == '1' ? 'selected' : '' }}>Quản trị viên</option>
        <option value="2" {{ request('role_id') == '2' ? 'selected' : '' }}>Khách hàng</option>
      </select>
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
          <th>Tên</th>
          <th>Email</th>
          <th>Vai trò</th>
          <th>Trạng thái</th>
          <th>Xác thực email</th>
          <th>Ngày tạo</th>
          <th>Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
          <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
              @if($user->role_id == 1)
                <span class="badge bg-danger">Quản trị viên</span>
              @else
                <span class="badge bg-secondary">Khách hàng</span>
              @endif
            </td>
            <td>
              @if($user->is_banned)
                <span class="badge bg-dark">Đã cấm</span>
              @else
                <span class="badge bg-success">Hoạt động</span>
              @endif
            </td>
            <td>
              @if($user->email_verified_at)
                <span class="text-success">✓ {{ $user->email_verified_at->format('d/m/Y') }}</span>
              @else
                <span class="text-muted">Chưa xác thực</span>
              @endif
            </td>
            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
            <td class="d-flex gap-2">
              <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-secondary">Xem</a>
              <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Sửa</a>
              @if($user->is_banned)
                <form action="{{ route('admin.users.unban', $user) }}" method="post">
                  @csrf
                  @method('PATCH')
                  <button class="btn btn-sm btn-success" type="submit">Bỏ cấm</button>
                </form>
              @else
                <form action="{{ route('admin.users.ban', $user) }}" method="post" onsubmit="return confirm('Bạn có chắc muốn cấm người dùng này?')">
                  @csrf
                  @method('PATCH')
                  <button class="btn btn-sm btn-danger" type="submit">Cấm</button>
                </form>
              @endif
            </td>
          </tr>
        @empty
          <tr><td colspan="8" class="text-center text-muted py-3">Chưa có người dùng nào.</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $users->links() }}
</div>
@endsection

