@extends('layouts.app')

@section('content')
<div class="container">

    <form method="POST" action="/domain/{{$domain->id}}">
        @csrf()
        @method('put')
        <div class="form-group">
            <label for="domainUrl">Domain Url</label>
            <input name="url" id="domainUrl" class="form-control" type="text" placeholder="Default input" required value="{{$domain->url}}">
        </div>

        @error('url')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label for="domainScore">Domain Score</label>
            <input name="score" id="domainScore" class="form-control" type="text" placeholder="Default input" required value="{{$domain->score}}">
        </div>

        @error('score')
        <div class=" alert alert-danger">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    @endsection
</div>
