@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Người dùng #{{ $user->id }}</h3>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">Sửa</a>
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
  </div>

  <div class="card p-3">
    <div class="row g-3">
      <div class="col-md-6">
        <div class="mb-3">
          <strong>Tên:</strong>
          <div>{{ $user->name }}</div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <strong>Email:</strong>
          <div>{{ $user->email }}</div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <strong>Vai trò:</strong>
          <div>
            @if($user->role_id == 1)
              <span class="badge bg-danger">Quản trị viên</span>
            @else
              <span class="badge bg-secondary">Khách hàng</span>
            @endif
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <strong>Trạng thái:</strong>
          <div>
            @if($user->is_banned)
              <span class="badge bg-dark">Đã cấm</span>
            @else
              <span class="badge bg-success">Hoạt động</span>
            @endif
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <strong>Xác thực email:</strong>
          <div>
            @if($user->email_verified_at)
              <span class="text-success">{{ $user->email_verified_at->format('d/m/Y H:i') }}</span>
            @else
              <span class="text-muted">Chưa xác thực</span>
            @endif
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <strong>Ngày tạo:</strong>
          <div>{{ $user->created_at->format('d/m/Y H:i') }}</div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <strong>Ngày cập nhật:</strong>
          <div>{{ $user->updated_at->format('d/m/Y H:i') }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

