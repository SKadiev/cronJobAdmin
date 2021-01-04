@extends('layouts.app')

@section('content')
<div class="container">
    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">Device Type</th>
            </tr>
        </thead>
        <tbody>

            @foreach($devices as $key => $device)

            <tr>
                <td>{{$device->type}}</td>
            </tr>
            @endforeach

        </tbody>
    </table>
</div>
@endsection
