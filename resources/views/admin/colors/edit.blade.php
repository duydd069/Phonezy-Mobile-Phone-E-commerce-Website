@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <h3 class="mb-3">Sửa màu sắc #{{ $color->id }}</h3>

  @if ($errors->any())
    <div class="alert alert-danger">
      <div class="fw-bold">Vui lòng kiểm tra lỗi:</div>
      <ul class="mb-0">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.colors.update', $color) }}" method="POST" class="row g-3">
    @csrf
    @method('PUT')

    <script>
    document.addEventListener('DOMContentLoaded', function() {
        const colorPicker = document.getElementById('hex_code_color');
        const hexInput = document.getElementById('hex_code');
        
        if (colorPicker && hexInput) {
            // Sync color picker to hex input
            colorPicker.addEventListener('input', function() {
                hexInput.value = this.value.toUpperCase();
            });
            
            // Sync hex input to color picker
            hexInput.addEventListener('input', function() {
                const value = this.value;
                if (/^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$/.test(value)) {
                    colorPicker.value = value;
                }
            });
            
            // Initialize
            if (hexInput.value) {
                colorPicker.value = hexInput.value;
            } else if (colorPicker.value) {
                hexInput.value = colorPicker.value.toUpperCase();
            }
        }
    });
    </script>

    <div class="col-md-6">
      <label class="form-label">Tên màu *</label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $color->name) }}" required maxlength="50">
    </div>

    <div class="col-md-6">
      <label class="form-label">Mã màu (Hex Code)</label>
      <input type="color" name="hex_code_color" id="hex_code_color" class="form-control form-control-color" value="{{ old('hex_code', $color->hex_code ?? '#4CD964') }}" style="width: 60px; height: 38px; cursor: pointer;">
      <input type="text" name="hex_code" id="hex_code" class="form-control mt-2" value="{{ old('hex_code', $color->hex_code) }}" placeholder="#FF0000" maxlength="7" pattern="^#([0-9A-Fa-f]{3}|[0-9A-Fa-f]{6})$">
      <small class="text-muted">Định dạng: #RRGGBB hoặc chọn từ color picker</small>
    </div>

    <div class="col-12">
      <label class="form-label">Xem trước:</label>
      <div>
        <span class="badge" style="background-color: {{ $color->hex_code ?? '#6c757d' }}; color: white; padding: 8px 15px; font-size: 14px;">
          {{ old('name', $color->name) }}
        </span>
      </div>
    </div>

    <div class="col-12 d-flex gap-2">
      <button class="btn btn-primary">Cập nhật</button>
      <a href="{{ route('admin.colors.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
  </form>
</div>
@endsection

