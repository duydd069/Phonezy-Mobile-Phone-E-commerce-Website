@extends('electro.layout')

@section('title', 'Đăng ký')

@section('content')
<div class="section">
  <div class="container">
    <div class="row">
      <div class="col-md-6 offset-md-3">
        <div class="billing-details card" style="padding:24px; margin-top:30px;">
          <h2 class="section-title">Register</h2>

          @if ($errors->any())
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

            <div class="form-group">
              <label for="name">Name</label>
              <input id="name" type="text" name="name" value="{{ old('name') }}" required class="input">
            </div>

            <div class="form-group">
              <label for="email">Email</label>
              <input id="email" type="email" name="email" value="{{ old('email') }}" required class="input">
            </div>

            <div class="form-group">
              <label for="password_hash">Password</label>
              <input id="password_hash" type="password" name="password_hash" required class="input">
            </div>

            <div class="form-group">
              <label for="password_hash_confirmation">Confirm Password</label>
              <input id="password_hash_confirmation" type="password" name="password_hash_confirmation" required class="input">
            </div>

            <div class="form-group">
              <button type="submit" class="primary-btn btn-block">Register</button>
            </div>
          </form>

          <p class="text-center">Do you have an account? <a href="{{ route('client.login') }}">Login</a></p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
