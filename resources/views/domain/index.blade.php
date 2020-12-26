@extends('layouts.app')

@section('content')
<div class="container">
    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">Domain Name</th>
                <th scope="col">Domain Score</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>

            </tr>
        </thead>
        <tbody>

            @foreach($domains as $key => $domain)

            <tr>
                <td>{{$domain->name}}</td>
                <td>{{$domain->score}}</td>
                <td><a href="/domain/{{$domain->id}}/edit" class="btn btn-primary " role="button">Edit Domain</a></td>
                <td>
                    <form method="POST" action="/domain/{{$domain->id}}">
                        @csrf()
                        @method('DELETE')
                        <button type="submit" class="btn btn-primary">Delete</button>

                    </form>
                </td>

            </tr>
            @endforeach

        </tbody>
    </table>
</div>
@endsection
