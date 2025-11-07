@extends('layouts.app')

@section('content')
<div class="container">
  <h1>Đăng ký tài khoản</h1>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('register.store') }}">
    @csrf

    <div class="form-group">
      <label for="name">Họ và tên</label>
      <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="form-control">
    </div>

    <div class="form-group">
      <label for="email">Email</label>
      <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-control">
    </div>

    <div class="form-group">
      <label for="password_hash">Mật khẩu</label>
      <input id="password_hash" type="password" name="password_hash" required class="form-control">
    </div>

    <div class="form-group">
      <label for="password_hash_confirmation">Xác nhận mật khẩu</label>
      <input id="password_hash_confirmation" type="password" name="password_hash_confirmation" required class="form-control">
    </div>
    <p>Bạn đã có tài khoản? <a href="{{ route('login') }}">Đăng nhập</a></p>
    <button type="submit" class="btn btn-primary">Đăng ký</button>
    
  </form>
</div>
@endsection
