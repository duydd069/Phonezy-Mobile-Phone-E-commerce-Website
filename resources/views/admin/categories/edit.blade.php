@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Edit Category</h3>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Back</a>
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
      <label class="form-label">Name</label>
      <input type="text" name="name" value="{{ old('name', $category->name) }}" class="form-control" required>
    </div>

    <div class="mb-3">
      <label class="form-label">Slug</label>
      <input type="text" name="slug" value="{{ old('slug', $category->slug) }}" class="form-control">
      <div class="form-text">If left blank, the slug will be generated automatically.</div>
    </div>

    <div class="mb-3">
      <label class="form-label">Description</label>
      <textarea name="description" rows="3" class="form-control">{{ old('description', $category->description) }}</textarea>
    </div>

    <button type="submit" class="btn btn-primary">Update</button>
  </form>
</div>
@endsection
