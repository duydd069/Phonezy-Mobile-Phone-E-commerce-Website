@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Brands</h3>
    <a href="{{ route('brands.create') }}" class="btn btn-primary">Create Brand</a>
  </div>

  <form method="get" class="row g-2 mb-3">
    <div class="col-auto">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name or slug">
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
  </form>

  @if(session('success'))
  <div class="alert alert-success">{{ session('success') }}</div>
  @endif

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Logo</th>
          <th>Name</th>
          <th>Slug</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($brands as $brand)
        <tr>
          <td>{{ $brand->id }}</td>
          <td>
            @if($brand->logo)
            <img src="{{ asset('storage/' . $brand->logo) }}" alt="logo" style="height:32px">
            @endif
          </td>
          <td>{{ $brand->name }}</td>
          <td>{{ $brand->slug }}</td>
          <td class="d-flex gap-2">
            <a href="{{ route('brands.show', $brand) }}" class="btn btn-sm btn-secondary">View</a>
            <a href="{{ route('brands.edit', $brand) }}" class="btn btn-sm btn-warning">Edit</a>
            <form action="{{ route('brands.destroy', $brand) }}" method="post" onsubmit="return confirm('Delete this brand?')">
              @csrf
              @method('DELETE')
              <button class="btn btn-sm btn-danger" type="submit">Delete</button>
            </form>
          </td>
        </tr>
        @empty
        <tr>
          <td colspan="5" class="text-center">No brands found</td>
        </tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $brands->links() }}
</div>
@endsection