@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">User #{{ $user->id }}</h3>
    <div class="d-flex gap-2">
      <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-warning">Edit</a>
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Back</a>
    </div>
  </div>

  <div class="card p-3">
    <div class="row g-3">
      <div class="col-md-6">
        <div class="mb-3">
          <strong>Name:</strong>
          <div>{{ $user->name }}</div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <strong>Email:</strong>
          <div>{{ $user->email }}</div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <strong>Role:</strong>
          <div>
            @if($user->is_admin)
              <span class="badge bg-danger">Admin</span>
            @else
              <span class="badge bg-secondary">User</span>
            @endif
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <strong>Email Verified At:</strong>
          <div>
            @if($user->email_verified_at)
              <span class="text-success">{{ $user->email_verified_at->format('d/m/Y H:i') }}</span>
            @else
              <span class="text-muted">Not verified</span>
            @endif
          </div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <strong>Created At:</strong>
          <div>{{ $user->created_at->format('d/m/Y H:i') }}</div>
        </div>
      </div>
      <div class="col-md-6">
        <div class="mb-3">
          <strong>Updated At:</strong>
          <div>{{ $user->updated_at->format('d/m/Y H:i') }}</div>
        </div>
      </div>
    </div>
  </div>
</div>
@endsection

