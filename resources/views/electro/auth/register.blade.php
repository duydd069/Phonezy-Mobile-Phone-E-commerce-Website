@extends('electro.layout')

@section('content')
<div class="container" style="padding: 40px 0;">
  <div class="row justify-content-center">
    <div class="col-md-6">
      <div class="card">
        <div class="card-body">
          <h2 class="mb-4">Register account</h2>

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

            <div class="form-group mb-3">
              <label for="name" class="form-label">Name</label>
              <input id="name" type="text" name="name" value="{{ old('name') }}" required autofocus class="form-control @error('name') is-invalid @enderror">
            </div>

            <div class="form-group mb-3">
              <label for="email" class="form-label">Email</label>
              <input id="email" type="email" name="email" value="{{ old('email') }}" required class="form-control @error('email') is-invalid @enderror">
            </div>

            <div class="form-group mb-3">
              <label for="password_hash" class="form-label">Password</label>
              <input id="password_hash" type="password" name="password_hash" required class="form-control @error('password_hash') is-invalid @enderror">
            </div>

            <div class="form-group mb-3">
              <label for="password_hash_confirmation" class="form-label">Password Confirm</label>
              <input id="password_hash_confirmation" type="password" name="password_hash_confirmation" required class="form-control @error('password_hash_confirmation') is-invalid @enderror">
            </div>

            <button type="submit" class="btn btn-success w-100">Register</button>
          </form>

          <p class="mt-3 text-center">
            Do you have an account? <a href="{{ route('client.login') }}">Login now</a>
          </p>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection
