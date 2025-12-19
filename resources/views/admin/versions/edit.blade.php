@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <h3 class="mb-3">Sửa phiên bản #{{ $version->id }}</h3>

  @if ($errors->any())
    <div class="alert alert-danger">
      <div class="fw-bold">Vui lòng kiểm tra lỗi:</div>
      <ul class="mb-0">
        @foreach ($errors->all() as $e) <li>{{ $e }}</li> @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.versions.update', $version) }}" method="POST" class="row g-3">
    @csrf
    @method('PUT')

    <div class="col-md-6">
      <label class="form-label">Tên phiên bản *</label>
      <input type="text" name="name" class="form-control" value="{{ old('name', $version->name) }}" required maxlength="50">
    </div>

    <div class="col-12 d-flex gap-2">
      <button class="btn btn-primary">Cập nhật</button>
      <a href="{{ route('admin.versions.index') }}" class="btn btn-secondary">Quay lại</a>
    </div>
  </form>
</div>
@endsection

