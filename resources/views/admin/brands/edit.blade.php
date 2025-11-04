@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <h3 class="mb-3">Edit Brand</h3>

  <form action="{{ route('brands.update', $brand) }}" method="post" enctype="multipart/form-data">
    @csrf
    @method('PUT')
    @include('admin.brands._form', ['brand' => $brand])
    <div class="d-flex gap-2">
      <button class="btn btn-primary" type="submit">Update</button>
      <a href="{{ route('brands.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
@endsection


