@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <h3 class="mb-3">Sửa dung lượng #{{ $storage->id }}</h3>

  @if ($errors->any())
    <div class="alert alert-danger">
      <div class="fw-bold">Vui lòng kiểm tra lỗi:</div>
      <ul class="mb-0">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.storages.update', $storage) }}" method="POST" class="row g-3">
    @csrf
    @method('PUT')

    <div class="col-md-6">
      <label class="form-label">Dung lượng *</label>
      <input type="text" name="storage" class="form-control" value="{{ old('storage', $storage->storage) }}" required maxlength="50">
    </div>

    <div class="col-12 d-flex gap-2">
      <button class="btn btn-primary">Cập nhật</button>
      <a href="{{ route('admin.storages.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
  </form>
</div>
@endsection

