@extends('layouts.app')

@section('content')
<div class="container">

    <form method="POST" action="/page/{{$page->id}}">
        @csrf()
        @method('put')
        <div class="form-group">
            <label for="pageBody">Page body</label>
            <input name="body" id="pageBody" class="form-control" type="text" placeholder="Default input" required value="{{$page->body}}">
        </div>

        @error('body')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <select class="form-control mb-2" name="domain_id" required>
            <option>Select Domain</option>
            @foreach ($domains as $key => $domain)
            <option value="{{ $key }}" {{ ( $key == $page->domain_id) ? 'selected' : '' }}>
                {{ $domain }}
            </option>
            @endforeach
        </select>

        <button type="submit" class="btn btn-primary">Submit</button>
    </form>
    @endsection
</div>
