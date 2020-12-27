@extends('layouts.app')

@section('content')
<div class="container">

    <form method="POST" action="/domain">
        @csrf()
        @method('post')
        <div class="form-group">
            <label for="domainName">Domain name</label>
            <input name="name" id="domainName" class="form-control" type="text" placeholder="Default input" required value="{{old('name')}}">
        </div>

        @error('name')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label for="domainScore">Domain Score</label>
            <input name="score" id="domainScore" class="form-control" type="text" placeholder="Default input" required value="{{old('score')}}"">
    </div>

    @error('score')
    <div class=" alert alert-danger">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn btn-secondary">Submit</button>
    </form>
    @endsection
</div>
