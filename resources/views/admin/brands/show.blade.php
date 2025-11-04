@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Brand #{{ $brand->id }}</h3>
    <div class="d-flex gap-2">
      <a href="{{ route('brands.edit', $brand) }}" class="btn btn-warning">Edit</a>
      <a href="{{ route('brands.index') }}" class="btn btn-secondary">Back</a>
    </div>
  </div>

  <div class="card p-3">
    <div class="row g-3 align-items-center">
      <div class="col-auto">
        @if($brand->logo)
          <img src="{{ asset('storage/' . $brand->logo) }}" alt="logo" style="height:64px">
        @endif
      </div>
      <div class="col">
        <div><strong>Name:</strong> {{ $brand->name }}</div>
        <div><strong>Slug:</strong> {{ $brand->slug }}</div>
      </div>
    </div>
  </div>
</div>
@endsection


