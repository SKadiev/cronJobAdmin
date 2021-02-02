@extends('layouts.app')

@section('content')
<div class="container">

    <form method="POST" action="/job/{{$job->id}}">
        @csrf()
        @method('put')

        <select class="form-control mb-2" name="job_type_id" required>
            <option selected>Select JobType</option>
            @foreach ($jobTypes as $key => $jobData)
            <option value="{{ $key }}" {{ ( $key == $job->job_type_id) ? 'selected' : '' }}>
                {{ $jobData }}
         
            @endforeach
        </select>
        
   
        <select class="form-control mb-2" name="rules_id" required>
            <option selected>Select Rule</option>
            @foreach ($rules as $key => $ruleData)
            <option value="{{ $key }}" {{ ( $key == $job->rules_id) ? 'selected' : '' }}>
            {{ $ruleData }}
          
            @endforeach
        </select>
        
        <div class="form-group">
            <label for="quantity">Number of videos to crawl</label>
            <br>
            <input type="number" id="quantity" name="videos_to_crawl" min="10" max="100" step="10"  required value="{{$job->videos_to_crawl}}">
        </div> 
        
        <button type="submit" class="btn btn-secondary">Submit</button>
    </form>
    @endsection

  
</div>
