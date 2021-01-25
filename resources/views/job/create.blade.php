@extends('layouts.app')

@section('content')
<div class="container">

    <form method="POST" action="/job">
        @csrf()
        @method('post')
        
        @error('url')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror
        
        <select class="form-control mb-2" name="rules_id" required>
            <option selected>Select Domain</option>
            @foreach ($rules as $key => $rule)
            <option value="{{ $rule }}">
                {{ $key }}
            </option>
            @endforeach
        </select>
        
        <div class="form-group">
            <label for="quantity">Number of videos to crawl</label>
            <br>
            <input type="number" id="quantity" name="videos_to_crawl" min="10" max="100" step="10"  required value="{{old('videos_to_crawl')}}">
        </div>
        
        <button type="submit" class="btn btn-secondary">Submit</button>
    </form>
    @endsection
</div>
