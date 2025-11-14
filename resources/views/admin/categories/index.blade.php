@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Categories</h3>
    <a href="{{ route('admin.categories.create') }}" class="btn btn-primary">Create Category</a>
  </div>

  {{-- Form tìm kiếm --}}
  <form method="get" class="row g-2 mb-3">
    <div class="col-auto">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name or slug">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
  </form>

  {{-- Thông báo thành công --}}
  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  {{-- Bảng dữ liệu --}}
  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Slug</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($categories as $category)
          <tr>
            <td>{{ $category->id }}</td>
            <td>{{ $category->name }}</td>
            <td>{{ $category->slug }}</td>
            <td class="d-flex gap-2">
              <a href="{{ route('admin.categories.show', $category) }}" class="btn btn-sm btn-secondary">View</a>
              <a href="{{ route('admin.categories.edit', $category) }}" class="btn btn-sm btn-warning">Edit</a>
              <form action="{{ route('admin.categories.destroy', $category) }}" method="post" onsubmit="return confirm('Delete this category?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" type="submit">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="4" class="text-center">No categories found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{-- Phân trang --}}
  {{ $categories->links() }}
</div>
@endsection
