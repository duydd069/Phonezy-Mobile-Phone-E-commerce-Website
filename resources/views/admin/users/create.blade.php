@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <h3 class="mb-3">Thêm người dùng mới</h3>

  <form action="{{ route('admin.users.store') }}" method="post" novalidate>
    @csrf
    @include('admin.users._form')
    <div class="d-flex gap-2">
      <button class="btn btn-primary" type="submit">Lưu</button>
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Hủy</a>
    </div>
  </form>
</div>
@endsection

