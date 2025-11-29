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
      <label class="form-label" for="category-name">Tên danh mục</label>
      <input type="text" id="category-name" name="name" value="{{ old('name', $category->name) }}" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label" for="category-slug">Slug</label>
      <input type="text" id="category-slug" name="slug" value="{{ old('slug', $category->slug) }}" class="form-control">
      <div class="form-text">Nếu để trống, slug sẽ được tạo tự động.</div>
    </div>

    <div class="mb-3">
      <label class="form-label">Mô tả</label>
      <textarea name="description" rows="3" class="form-control">{{ old('description', $category->description) }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Cập nhật</button>
  </form>
</div>
<script>
document.addEventListener('DOMContentLoaded', function () {
  const nameInput = document.getElementById('category-name');
  const slugInput = document.getElementById('category-slug');

  if (!nameInput || !slugInput) return;

  const slugify = (value) => value
    .toString()
    .normalize('NFD').replace(/[\u0300-\u036f]/g, '')
    .toLowerCase()
    .replace(/[^a-z0-9\s-]/g, '')
    .trim()
    .replace(/\s+/g, '-')
    .replace(/-+/g, '-');

  let manualSlug = slugInput.value.trim().length > 0;

  slugInput.addEventListener('input', () => {
    manualSlug = slugInput.value.trim().length > 0;
  });

  slugInput.addEventListener('blur', () => {
    if (!slugInput.value.trim()) {
      manualSlug = false;
      slugInput.value = slugify(nameInput.value);
    }
  });

  nameInput.addEventListener('input', () => {
    if (manualSlug) return;
    slugInput.value = slugify(nameInput.value);
  });
});
</script>
@endsection
