@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Sửa danh mục</h3>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul class="mb-0">
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form action="{{ route('admin.categories.update', $category) }}" method="POST" class="card card-body shadow-sm">
    @csrf
    @method('PUT')

    <div class="mb-3">
      <label class="form-label">Tên danh mục</label>
      <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Slug</label>
      <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="form-control">
      <div class="form-text">Nếu để trống, slug sẽ được tạo tự động.</div>
    </div>

    <div class="mb-3">
      <label class="form-label">Mô tả</label>
      <textarea name="description" rows="3" class="form-control">{{ old('description', $category->description) }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật</button>
  </form>
</div>
@endsection
