@extends('layouts.app')

@section('content')
<div class="container">

    <form method="POST" action="/channel">
        @csrf()
        @method('post')
        <div class="form-group">
            <label for="channelName">Channel Name</label>
            <input name="channelName" id="channelName" class="form-control" type="text" placeholder="Channel Username" required value="{{old('channelName')}}">
        </div>

        @error('channelName')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label for="channelUsername">Channel Username</label>
            <input name="channelUsername" id="channelUsername" class="form-control" type="text" placeholder="Channel Username" required value="{{old('channelUsername')}}">
        </div>

        @error('channelUsername')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        
        <div class="form-group">
            <label for="channelId">Channel ID</label>
            <input name="channelId" id="channelId" class="form-control" type="text" placeholder="Channel ID" required value="{{old('channelId')}}">
        </div>

        @error('channelId')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        
        <div class="form-group">
            <label for="score">Channel Score</label>
            <input name="score" id="score" class="form-control" type="text" placeholder="Channel Score" required value="{{old('score')}}"">
    </div>

    @error('score')
    <div class=" alert alert-danger">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn btn-secondary">Submit</button>
    </form>
    @endsection
</div>
