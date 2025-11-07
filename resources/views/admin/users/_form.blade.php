<div class="mb-3">
  <label class="form-label">Tên tài khoản <span class="text-danger">*</span></label>
  <input type="text" name="name" value="{{ old('name', $user->name ?? '') }}" class="form-control @error('name') is-invalid @enderror">
  @error('name')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
  <label class="form-label">Email <span class="text-danger">*</span></label>
  <input type="email" name="email" value="{{ old('email', $user->email ?? '') }}" class="form-control @error('email') is-invalid @enderror">
  @error('email')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
  <label class="form-label">Mật khẩu <span class="text-danger">*</span> <small class="text-muted"></small></label>
  <input type="password" name="password" class="form-control @error('password') is-invalid @enderror">
  @error('password')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
  <div class="form-check">
    <input type="checkbox" name="is_admin" value="1" class="form-check-input" id="is_admin" {{ old('is_admin', $user->is_admin ?? 0) ? 'checked' : '' }}>
    <label class="form-check-label" for="is_admin">
      Là Admin
    </label>
  </div>
  @error('is_admin')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

<div class="mb-3">
  <label class="form-label">Email xác thực</label>
  <input type="datetime-local" name="email_verified_at" value="{{ old('email_verified_at', isset($user) && $user->email_verified_at ? $user->email_verified_at->format('Y-m-d\TH:i') : '') }}" class="form-control">
  @error('email_verified_at')<div class="text-danger small">{{ $message }}</div>@enderror
</div>

