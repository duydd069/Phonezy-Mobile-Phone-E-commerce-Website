@extends('electro.layout')

@section('content')
<div class="container" style="padding: 40px 0;">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h2 class="mb-4">Login</h2>

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

            <div class="form-group mb-3">
              <label for="email" class="form-label">Email</label>
              <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus class="form-control @error('email') is-invalid @enderror">
            </div>

            <div class="form-group mb-3">
              <label for="password" class="form-label">Password</label>
              <input id="password" type="password" name="password" required class="form-control @error('password') is-invalid @enderror">
            </div>

            <div class="form-group form-check mb-3">
              <input type="checkbox" name="remember" id="remember" class="form-check-input" {{ old('remember') ? 'checked' : '' }}>
              <label for="remember" class="form-check-label">Remember me</label>
            </div>

            <button type="submit" class="btn btn-primary w-100">Login</button>
          </form>

          <p class="mt-3 text-center">
            Don't have an account? <a href="{{ route('client.register') }}">Register now</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
