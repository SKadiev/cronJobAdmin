@extends('layouts.app')

@section('content')
<div class="container">

    <form method="POST" action="/channel">
        @csrf()
        @method('post')
        <div class="form-group">
            <label for="channel_name">Channel url</label>
            <input name="channel_url" id="channel_name" class="form-control" type="text" placeholder="Channel Username"  value="{{old('channel_url')}}">
        </div>

        <button type="submit" class="btn btn-secondary">Submit</button>
    </form>
    @endsection
</div>
