@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <h3 class="mb-3">Thêm thương hiệu mới</h3>

  <form action="{{ route('admin.brands.store') }}" method="post" enctype="multipart/form-data" novalidate>
    @csrf
    @include('admin.brands._form')
    <div class="d-flex gap-2">
      <button class="btn btn-primary" type="submit">Lưu</button>
      <a href="{{ route('admin.brands.index') }}" class="btn btn-secondary">Hủy</a>
    </div>
  </form>
</div>
@endsection


