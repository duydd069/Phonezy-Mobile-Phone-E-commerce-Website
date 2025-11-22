@extends('layouts.app')

@section('title', 'Thêm danh mục mới')

@section('content')
<div class="container mt-4">
    <h2 class="mb-4">Thêm danh mục mới</h2>

    {{-- Hiển thị thông báo lỗi --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Form thêm mới --}}
    <form action="{{ route('admin.categories.store') }}" method="POST">
        @csrf
        <div class="mb-3">
            <label for="category-name" class="form-label">Tên danh mục</label>
            <input type="text" name="name" id="category-name" class="form-control" value="{{ old('name') }}" required>
        </div>
        <div class="mb-3">
            <label for="category-slug" class="form-label">Slug</label>
            <input type="text" name="slug" id="category-slug" class="form-control" value="{{ old('slug') }}">
            <div class="form-text">Nếu để trống, slug sẽ được tạo tự động từ tên.</div>
        </div>
        <button type="submit" class="btn btn-primary">Lưu danh mục</button>
        <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
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
