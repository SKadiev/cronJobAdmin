@extends('layouts.app')

@section('content')
<div class="container">
    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">User Name</th>
                <th scope="col">Role type</th>
                @if ($userRole === 'admin')
                    <th scope="col">Edit</th>
                @endif
                <th scope="col">DELETE</th>

            </tr>
        </thead>
        <tbody>
            @foreach($users as $key => $user)

            <tr>
                <td>{{$user->username}}</td>
                <td>{{$user->roletype}}
                    @if ($userRole === 'admin')
                    <td>
                     <form method="POST" action="/user/{{$user->id}}">
                         @csrf()
                         @method('DELETE')
                         <button type="submit" class="btn btn-secondary">Edit</button>
                     </form>
                    </td>
                 @endif
                <td>
                    <form method="POST" action="/user/{{$user->id}}">
                        @csrf()
                        @method('DELETE')
                        <button onclick="return confirm('Are you sure? You will lose user data')" type="submit" class="btn btn-secondary">Delete</button>
                    </form>
                </td>

            </tr>
            @endforeach

        </tbody>
    </table>
</div>
@endsection
