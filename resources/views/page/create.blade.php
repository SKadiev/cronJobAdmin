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

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    @endsection
</div>
