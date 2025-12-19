@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <h3 class="mb-3">Thêm dung lượng mới</h3>

  @if ($errors->any())
    <div class="alert alert-danger">
      <div class="fw-bold">Vui lòng kiểm tra lỗi:</div>
      <ul class="mb-0">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.storages.store') }}" method="POST" class="row g-3">
    @csrf

    <div class="col-md-6">
      <label class="form-label">Dung lượng *</label>
      <input type="text" name="storage" class="form-control" value="{{ old('storage') }}" required maxlength="50">
      <small class="text-muted">VD: 64GB, 128GB, 256GB, 512GB, 1TB...</small>
    </div>

    <div class="col-12 d-flex gap-2">
      <button class="btn btn-primary">Lưu</button>
      <a href="{{ route('admin.storages.index') }}" class="btn btn-secondary">Hủy</a>
    </div>
  </form>
</div>
@endsection

