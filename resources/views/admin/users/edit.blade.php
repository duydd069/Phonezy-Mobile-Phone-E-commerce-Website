@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <h3 class="mb-3">Sửa người dùng</h3>

  <form action="{{ route('admin.users.update', $user) }}" method="post">
    @csrf
    @method('PUT')
    @include('admin.users._form', ['user' => $user])
    <div class="d-flex gap-2">
      <button class="btn btn-primary" type="submit">Cập nhật</button>
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Hủy</a>
    </div>
  </form>
</div>
@endsection

