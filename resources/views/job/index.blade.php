@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/job/create" class="btn btn-secondary  mb-2 mr-2 pull-right" role="button">Create job</a>

    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">Rule name</th>
                <th scope="col">Number of videos to crawl</th>
                <th scope="col">Videos from score</th>
                <th scope="col">Videos from to</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
                <th scope="col">Run job</th>

            </tr>
        </thead>
        <tbody>



            @foreach($jobs as $key => $job)

            <tr>
                <td>{{$job->name}}</td>
                <td>{{($job->videos_to_crawl)}} </td>
                <td>{{($job->from)}} </td>
                <td>{{($job->to)}} </td>

                <td><a href="/job/{{$job->id}}/edit" class="btn btn-secondary " role="button">Edit Job</a></td>
                <td>
                    <form method="POST" action="/job/{{$job->id}}">
                        @csrf()
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary">Delete</button>

                    </form>
                </td>

                <td>
                    <form id="jobForm" method="GET" action="job/{{$job->from}}/{{$job->to}}">
                        @csrf()
                        <button type="submit" class="btn btn-secondary">Run job</button>

                    </form>
                </td>
                

            </tr>
            @endforeach

        </tbody>
    </table>
</div>
<script>

    let form = document.getElementById('jobForm');

    form.addEventListener('submit', function(e) {

        // e.preventDefault();
        

    });
</script>
@endsection
