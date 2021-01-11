@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/rule/create" class="btn btn-secondary  mb-2 mr-2 pull-right" role="button">Create Rule</a>

    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">Rule Name</th>
                <th scope="col">From</th>
                <th scope="col">To</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>

            </tr>
        </thead>
        <tbody>
            @foreach($rules as $key => $rule)

            <tr>
                <td>{{$rule->name}}</td>
                <td>{{$rule->from}}</td>
                <td>{{$rule->to}}</td>
                <td><a href="/rule/{{$rule->id}}/edit" class="btn btn-secondary " role="button">Edit Rule</a></td>

                <td>
                    <form method="POST" action="/rule/{{$rule->id}}">
                        @csrf()
                        @method('DELETE')
                        <button onclick="return confirm('Are you sure? You will lose rule data')" type="submit" class="btn btn-secondary">Delete</button>
                    </form>
                </td>

                
                <td>
               
            </td>
            </tr>
            @endforeach

        </tbody>
    </table>
</div>
@endsection
