@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <h3 class="mb-3">Create Brand</h3>

  <form action="{{ route('brands.store') }}" method="post" enctype="multipart/form-data">
    @csrf
    @include('admin.brands._form')
    <div class="d-flex gap-2">
      <button class="btn btn-primary" type="submit">Save</button>
      <a href="{{ route('brands.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
@endsection


