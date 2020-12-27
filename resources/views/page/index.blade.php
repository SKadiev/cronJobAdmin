@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/page/create" class="btn btn-secondary  mb-2 mr-2 pull-right" role="button">Create page</a>

    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">Page body</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>

            </tr>
        </thead>
        <tbody>

            @foreach($pages as $key => $page)

            <tr>
                <td>{{$page->body}}</td>
                <td><a href="/page/{{$page->id}}/edit" class="btn btn-secondary " role="button">Edit Page</a></td>
                <td>
                    <form method="POST" action="/page/{{$page->id}}">
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
