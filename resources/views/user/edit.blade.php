@extends('layouts.app')

@section('content')
<div class="container">

    <form method="POST" action="/user/{{$user->id}}">
        @csrf()
        @method('put')
        <div class="form-group">
            <label for="name">User Name</label>
            <input name="name" id="name" class="form-control" type="text" placeholder="Username" required value="{{$user->name}}">
        </div>

        @error('username')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <select class="form-control mb-2" name="roles_id" required>
            <option>Select Role</option>
            @foreach ($roles as $key => $role)
            <option value="{{ $key }}" {{ ( $role == "admin") ? 'selected' : '' }}>
                {{ $role }}
            </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-secondary">Submit</button>
    </form>
    @endsection
</div>
