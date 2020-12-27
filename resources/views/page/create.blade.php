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

        <select class="form-control mb-2" name="domain_id" required>
            <option selected>Select Domain</option>
            @foreach ($domains as $key => $domain)
            <option value="{{ $key }}">
                {{ $domain }}
            </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-secondary">Submit</button>
    </form>
    @endsection
</div>
