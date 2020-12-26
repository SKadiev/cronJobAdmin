@extends('layouts.app')

@section('content')
<div class="container">

    <form method="POST" action="/domain/{{$domain->id}}">
        @csrf()
        @method('put')
        <div class="form-group">
            <label for="name">Domain Name</label>
            <input name="name" id="name" class="form-control" type="text" placeholder="Default input" required value="{{$domain->name}}">
        </div>

        @error('name')
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
