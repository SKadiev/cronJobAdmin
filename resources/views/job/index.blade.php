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
                <th scope="col">Type</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>
                <th scope="col">Run job</th>

            </tr>
        </thead>
        <tbody>



            @foreach($jobs as $key => $job)

            <tr>
                <td>{{$job->name}}</td>
                <td class="videosToCrawl">{{($job->videos_to_crawl)}} </td>
                <td class="jobsFrom">{{($job->from)}} </td>
                <td class="jobsTo">{{($job->to)}} </td>
                <td class="jobsType">{{($job->type)}} </td>
                <td><a href="/job/{{$job->id}}/edit" class="btn btn-secondary" role="button">Edit Job</a></td>
                <td>
                    <form method="POST" action="/job/{{$job->id}}">
                        @csrf()
                        @method('DELETE')
                        <button type="submit" class="btn btn-secondary">Delete</button>

                    </form>
                </td>

                <td>
                    {{-- <form id="jobForm" method="GET" action="job/{{$job->from}}/{{$job->to}}/{{$job->type}}"> --}}
                    <button class="btn btn-secondary runJobBtn">Run job</button>
                </td>
                

            </tr>
            @endforeach

        </tbody>
    </table>
</div>
@section('footerScripts')
@parent

<script>
    
    let runJobBtn = document.querySelectorAll('.runJobBtn');

    runJobBtn.forEach(job => job.addEventListener('click', function() {
        let tableRow = $(this).parent().parent(); 
        // console.log(tableRow, this);
        let videosToCrawl = tableRow.find('.videosToCrawl').text();
        let jobsFrom = tableRow.find('.jobsFrom').text();
        let jobsTo = tableRow.find('.jobsTo').text();
        let jobsType = tableRow.find('.jobsType').text();
        // console.log(jobsFrom);
        $.ajax({
            type:'GET',
            url:`job/${jobsFrom}/${jobsTo}/${jobsType}`,
            success:function(data){
                alert(data);
            }
        });
    }));

   
</script>
@endsection
@endsection
