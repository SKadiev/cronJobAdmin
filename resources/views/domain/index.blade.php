@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/domain/create" class="btn btn-secondary  mb-2 mr-2 pull-right" role="button">Create domain</a>

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
                <td><a href="/domain/{{$domain->id}}/edit" class="btn btn-secondary " role="button">Edit Domain</a></td>
                <td>
                    <form method="POST" action="/domain/{{$domain->id}}">
                        @csrf()
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary">Delete</button>

                    </form>
                </td>

            </tr>
            @endforeach

        </tbody>
    </table>
</div>
@endsection
