@extends('layouts.app')

@section('content')
<div class="container">
    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">Device Type</th>
                <th scope="col">Delete</th>
                <th scope="col">UserName</th>
            </tr>
        </thead>
        <tbody>
            @foreach($devices as $key => $device)

            <tr>
                <td>{{$device->type}}</td>
                <td>
                    <form method="POST" action="/device/{{$device->id}}">
                        @csrf()
                        @method('DELETE')
                        <button onclick="return confirm('Are you sure? You will lose device data')" type="submit" class="btn btn-secondary">Delete</button>
                    </form>
                </td>

                
                <td>
                @if ($userRole === 'admin')
                    {{$device->name}}
                @else
                    {{$device->user->name}}
                @endif
            </td>
            </tr>
            @endforeach

        </tbody>
    </table>
</div>
@endsection
