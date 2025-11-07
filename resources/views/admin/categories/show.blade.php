@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Category Details</h3>
    <a href="{{ route('admin.categories.index') }}" class="btn btn-secondary">Back</a>
  </div>

  <div class="card shadow-sm">
    <div class="card-body">
      <p><strong>Name:</strong> {{ $category->name }}</p>
      <p><strong>Slug:</strong> {{ $category->slug }}</p>

      @if($category->description)
        <p><strong>Description:</strong><br>{{ $category->description }}</p>
      @endif

      <p><strong>Created at:</strong> {{ $category->created_at->format('d/m/Y H:i') }}</p>
      <p><strong>Updated at:</strong> {{ $category->updated_at->format('d/m/Y H:i') }}</p>
    </div>
  </div>
</div>
@endsection
