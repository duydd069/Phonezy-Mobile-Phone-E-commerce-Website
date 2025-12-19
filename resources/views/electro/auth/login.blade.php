@extends('electro.layout')

@section('title', 'Đăng nhập')

@section('content')
<div class="section">
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <div class="billing-details card" style="padding:24px; margin-top:30px;">
          <h2 class="section-title">Login</h2>

          @if ($errors->any())
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

            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="input">
            </div>

            <div class="form-group">
              <label for="password">Password</label>
              <input id="password" type="password" name="password" required class="input">
            </div>

            <div class="form-group">
              <label class="checkbox-inline">
                <input type="checkbox" name="remember" {{ old('remember') ? 'checked' : '' }}> Remember me
              </label>
            </div>

            <div class="form-group">
              <button type="submit" class="primary-btn btn-block">Login</button>
            </div>
          </form>

          <p class="text-center">Do you not have an account? <a href="{{ route('client.register') }}">Register</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
