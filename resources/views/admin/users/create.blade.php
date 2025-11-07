@extends('layouts.app')

@section('content')
<div class="container-fluid p-3">
  <h3 class="mb-3">Create User</h3>

  <form action="{{ route('admin.users.store') }}" method="post">
    @csrf
    @include('admin.users._form')
    <div class="d-flex gap-2">
      <button class="btn btn-primary" type="submit">Save</button>
      <a href="{{ route('admin.users.index') }}" class="btn btn-secondary">Cancel</a>
    </div>
  </form>
</div>
@endsection

