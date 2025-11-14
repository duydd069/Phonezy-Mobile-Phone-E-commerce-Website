@extends('electro.layout')

@section('content')
<div class="container">
  <h1>Đăng nhập</h1>

  @if ($errors->any())
    <div class="alert alert-danger">
      <ul>
        @foreach ($errors->all() as $error)
          <li>{{ $error }}</li>
        @endforeach
      </ul>
    </div>
  @endif

  <form method="POST" action="{{ route('client.login.post') }}">
    @csrf

    <div class="form-group">
      <label for="email">Email</label>
      <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control">
    </div>

    <div class="form-group">
      <label for="password">Mật khẩu</label>
      <input id="password" type="password" name="password" required class="form-control">
    </div>

    <div class="form-group form-check">
      <input type="checkbox" name="remember" id="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
      <label for="remember" class="form-check-label">Ghi nhớ tôi</label>
    </div>

    <button type="submit" class="btn btn-primary">Đăng nhập</button>
  </form>

  <p>Bạn chưa có tài khoản? <a href="{{ route('client.register') }}">Đăng ký</a></p>
</div>
@endsection
