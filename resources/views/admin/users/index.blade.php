@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <div class="d-flex justify-content-between align-items-center mb-3">
    <h3 class="m-0">Users</h3>
    <a href="{{ route('admin.users.create') }}" class="btn btn-primary">Create User</a>
  </div>

  <form method="get" class="row g-2 mb-3">
    <div class="col-auto">
      <input type="text" name="q" value="{{ request('q') }}" class="form-control" placeholder="Search name or email">
    </div>
    <div class="col-auto">
      <select name="is_admin" class="form-select">
        <option value="">All Roles</option>
        <option value="1" {{ request('is_admin') == '1' ? 'selected' : '' }}>Admin</option>
        <option value="0" {{ request('is_admin') == '0' ? 'selected' : '' }}>User</option>
      </select>
    </div>
    <div class="col-auto">
      <button class="btn btn-outline-secondary" type="submit">Search</button>
    </div>
  </form>

  @if(session('success'))
    <div class="alert alert-success">{{ session('success') }}</div>
  @endif
  @if(session('error'))
    <div class="alert alert-danger">{{ session('error') }}</div>
  @endif

  <div class="table-responsive">
    <table class="table table-striped align-middle">
      <thead>
        <tr>
          <th>ID</th>
          <th>Name</th>
          <th>Email</th>
          <th>Role</th>
          <th>Email Verified</th>
          <th>Created At</th>
          <th>Actions</th>
        </tr>
      </thead>
      <tbody>
        @forelse($users as $user)
          <tr>
            <td>{{ $user->id }}</td>
            <td>{{ $user->name }}</td>
            <td>{{ $user->email }}</td>
            <td>
              @if($user->is_admin)
                <span class="badge bg-danger">Admin</span>
              @else
                <span class="badge bg-secondary">User</span>
              @endif
            </td>
            <td>
              @if($user->email_verified_at)
                <span class="text-success">✓ {{ $user->email_verified_at->format('d/m/Y') }}</span>
              @else
                <span class="text-muted">Not verified</span>
              @endif
            </td>
            <td>{{ $user->created_at->format('d/m/Y H:i') }}</td>
            <td class="d-flex gap-2">
              <a href="{{ route('admin.users.show', $user) }}" class="btn btn-sm btn-secondary">View</a>
              <a href="{{ route('admin.users.edit', $user) }}" class="btn btn-sm btn-warning">Edit</a>
              <form action="{{ route('admin.users.destroy', $user) }}" method="post" onsubmit="return confirm('Delete this user?')">
                @csrf
                @method('DELETE')
                <button class="btn btn-sm btn-danger" type="submit">Delete</button>
              </form>
            </td>
          </tr>
        @empty
          <tr><td colspan="7" class="text-center">No users found</td></tr>
        @endforelse
      </tbody>
    </table>
  </div>

  {{ $users->links() }}
</div>
@endsection

