@extends('layouts.app')

@section('content')
<div class="container">

    <form method="POST" action="/page">
        @csrf()
        @method('post')
        <div class="form-group">
            <label for="pageBody">Page body</label>
            <input name="body" id="pageBody" class="form-control" type="text" placeholder="Default input" required value="{{old('body')}}">
        </div>

        @error('url')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <form class="form-inline d-flex justify-content-center md-form form-sm mt-0">
            <i class="fas fa-search" aria-hidden="true"></i>
            <input class="form-control form-control-sm ml-3 w-75" type="text" placeholder="Search" aria-label="Search">
        </form>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    @endsection
</div>
