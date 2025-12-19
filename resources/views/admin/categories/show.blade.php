@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Chi tiết danh mục</h3>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Quay lại</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <p><strong>Tên:</strong> {{ $category->name }}</p>
      <p><strong>Slug:</strong> {{ $category->slug }}</p>

      @if($category->description)
        <p><strong>Mô tả:</strong><br>{{ $category->description }}</p>
      @endif

      <p><strong>Ngày tạo:</strong> {{ $category->created_at->format('d/m/Y H:i') }}</p>
      <p><strong>Ngày cập nhật:</strong> {{ $category->updated_at->format('d/m/Y H:i') }}</p>
    </div>
  </div>
</div>
@endsection
