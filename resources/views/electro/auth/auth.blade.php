@extends('electro.layout')

@section('content')
<div class="container" style="padding:40px 0">
  <div class="row">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h3 class="mb-3">Đăng nhập</h3>

          @if ($errors->any() && old('_form') === 'login')
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <form method="POST" action="{{ route('client.login.post') }}">
            @csrf
            <input type="hidden" name="_form" value="login">

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
              <label for="remember" class="form-check-label">remember me</label>
            </div>

            <button type="submit" class="btn btn-primary">login</button>
          </form>
        </div>
      </div>
    </div>

    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h3 class="mb-3">Register</h3>

          @if ($errors->any() && old('_form') === 'register')
          <div class="alert alert-danger">
            <ul class="mb-0">
              @foreach ($errors->all() as $error)
                <li>{{ $error }}</li>
              @endforeach
            </ul>
          </div>
          @endif

          <form method="POST" action="{{ route('client.register.store') }}">
            @csrf
            <input type="hidden" name="_form" value="register">

            <div class="form-group">
              <label for="name">Name</label>
              <input id="name" type="text" name="name" value="{{ old('name') }}" required class="form-control">
            </div>

            <div class="form-group">
              <label for="email_reg">Email</label>
              <input id="email_reg" type="email" name="email" value="{{ old('email') }}" required class="form-control">
            </div>

            <div class="form-group">
              <label for="password_hash">Password</label>
              <input id="password_hash" type="password" name="password_hash" required class="form-control">
            </div>

            <div class="form-group">
              <label for="password_hash_confirmation">Password confirm</label>
              <input id="password_hash_confirmation" type="password" name="password_hash_confirmation" required class="form-control">
            </div>

            <button type="submit" class="btn btn-success">Register</button>
          </form>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
