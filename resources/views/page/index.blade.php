@extends('layouts.app')

@section('content')
<div class="container">
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
                <td><a href="/page/{{$page->id}}/edit" class="btn btn-primary " role="button">Edit Page</a></td>
                <td>
                    <form method="POST" action="/page/{{$page->id}}">
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
