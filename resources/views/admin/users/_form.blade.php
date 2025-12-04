<div class="mb-3">
  <label class="form-label">Tên <span class="text-danger">*</span></label>
  <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control" required>
  @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
  <label class="form-label">Email <span class="text-danger">*</span></label>
  <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control" required>
  @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
  <label class="form-label">Mật khẩu <span class="text-danger">*</span> 
    @if(isset($user))
      <small class="text-muted">(Để trống khi sửa để giữ mật khẩu hiện tại)</small>
    @endif
  </label>
  <input type="password" name="password" class="form-control" {{ !isset($user) ? 'required' : '' }}>
  @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
  <label class="form-label">Vai trò <span class="text-danger">*</span></label>
  <select name="role_id" class="form-select" required>
    <option value="2" {{ old('role_id', $user->role_id ?? 2) == 2 ? 'selected' : '' }}>Khách hàng</option>
    <option value="1" {{ old('role_id', $user->role_id ?? 2) == 1 ? 'selected' : '' }}>Quản trị viên</option>
  </select>
  @error('role_id')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

@if(isset($user))
  <div class="mb-3">
    <label class="form-label">Ngày xác thực email</label>
    <input type="datetime-local" name="email_verified_at" value="{{ old('email_verified_at', $user->email_verified_at ? $user->email_verified_at->format('Y-m-d\TH:i') : '') }}" class="form-control">
    @error('email_verified_at')<div class="text-danger small">{{ $message }}</div>@enderror
  </div>
@endif

