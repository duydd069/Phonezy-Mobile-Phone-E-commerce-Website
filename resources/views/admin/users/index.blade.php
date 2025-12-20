@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Quản lý người dùng</h3>
  </div>

  <form method="get" class="row g-2 mb-3">
    <div class="col-md-3">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Tìm theo tên hoặc email">
    </div>
    <div class="col-md-2">
      <select name="role_id" class="form-select">
        <option value="">Tất cả vai trò</option>
        <option value="1" {{ request('role_id') == '1' ? 'selected' : '' }}>Admin</option>
        <option value="2" {{ request('role_id') == '2' ? 'selected' : '' }}>User</option>
      </select>
    </div>
    <div class="col-md-2">
      <select name="status" class="form-select">
        <option value="">Tất cả trạng thái</option>
        <option value="active" {{ request('status') == 'active' ? 'selected' : '' }}>Hoạt động</option>
        <option value="banned" {{ request('status') == 'banned' ? 'selected' : '' }}>Bị cấm</option>
        <option value="inactive" {{ request('status') == 'inactive' ? 'selected' : '' }}>Không hoạt động</option>
      </select>
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary" type="submit">Tìm kiếm</button>
      <a href="{{ route('admin.users.index') }}" class="btn btn-outline-danger">Xóa bộ lọc</a>
    </div>
  </form>

  @if(session('success'))
    <div class="alert alert-success alert-dismissible fade show">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      {{ session('success') }}
    </div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger alert-dismissible fade show">
      <button type="button" class="close" data-dismiss="alert">&times;</button>
      {{ session('error') }}
    </div>
  @endif

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th style="width: 50px">STT</th>
          <th>Tên</th>
          <th>Email</th>
          <th>Vai trò</th>
          <th>Trạng thái</th>
          <th>Xác thực email</th>
          <th>Ngày tạo</th>
          <th style="width: 200px">Thao tác</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $index => $user)
          <tr>
            <td>{{ ($users->currentPage() - 1) * $users->perPage() + $index + 1 }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
              @if($user->role_id == 1)
                <span class="badge bg-danger">Admin</span>
              @elseif($user->role_id == 2)
                <span class="badge bg-primary">User</span>
              @elseif($user->roles && $user->roles->isNotEmpty())
                @foreach($user->roles as $role)
                  <span class="badge bg-info">{{ $role->name }}</span>
                @endforeach
              @else
                <span class="badge bg-secondary">Chưa có vai trò</span>
              @endif
            </td>
            <td>
              @if($user->status == 'active')
                <span class="badge bg-success">Hoạt động</span>
              @elseif($user->status == 'banned')
                <span class="badge bg-danger">Bị cấm</span>
              @else
                <span class="badge bg-secondary">Không hoạt động</span>
              @endif
            </td>
            <td>
              @if($user->email_verified_at)
                <span class="text-success">✓ {{ $user->email_verified_at->format('d/m/Y') }}</span>
              @else
                <span class="text-muted">Chưa xác thực</span>
              @endif
            </td>

            {{-- <td>{{ optional($user->created_at)->format('d/m/Y H:i') ?? 'N/A' }}</td>
            <td class="d-flex gap-2">
              <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-secondary">Xem</a>
              <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Sửa</a>
            </td> --}}

            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
            <td>
              <div class="btn-group" role="group">
                <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-info">
                  <i class="bi bi-eye"></i> Xem
                </a>
                <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">
                  <i class="bi bi-pencil"></i> Sửa
                </a>
                
                @if($user->status == 'banned')
                  <form action="{{ route('admin.users.unban', $user) }}" method="post" class="d-inline">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-sm btn-success" type="submit" title="Bỏ cấm">
                      <i class="bi bi-unlock"></i> Unban
                    </button>
                  </form>
                @else
                  <form action="{{ route('admin.users.ban', $user) }}" method="post" class="d-inline" onsubmit="return confirm('Bạn có chắc muốn cấm người dùng này?')">
                    @csrf
                    @method('PUT')
                    <button class="btn btn-sm btn-danger" type="submit" title="Cấm">
                      <i class="bi bi-lock"></i> Ban
                    </button>
                  </form>
                @endif
              </div>

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
