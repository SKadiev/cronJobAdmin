@extends('layouts.app')

@section('content')
<div class="container">

    <form method="POST" action="/channel/{{$channel->id}}">
        @csrf()
        @method('put')
        <div class="form-group">
            <label for="channel_name">Channel Name</label>
            <input name="channel_name" id="channel_name" class="form-control" type="text" placeholder="Channel Username"  value="{{$channel->channel_name}}">
        </div>

        @error('channel_name')
        <div class="alert alert-danger">{{  $message }}</div>
        @enderror

        <div class="form-group">
            <label for="channel_username">Channel Username</label>
            <input name="channel_username" id="channel_username" class="form-control" type="text" placeholder="Channel Username"   value="{{$channel->channel_username}}">
        </div>

        @error('channel_username')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        
        <div class="form-group">
            <label for="channel_id">Channel ID</label>
            <input name="channel_id" id="channel_id" class="form-control" type="text" placeholder="Channel ID"   value="{{$channel->channel_id}}">
        </div>

        @error('channel_id')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label for="subscribers">Subscribers</label>
            <input name="subscribers" id="subscribers" class="form-control" type="number" placeholder="0"  value="{{$channel->subscribers}}">
        </div>

        @error('subscribers')
        <div class="alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label for="views">Views</label>
            <input name="views" id="views" class="form-control" type="number" placeholder="0"   value="{{$channel->views_count}}">
        </div>

        @error('views')
        <div class=" alert alert-danger">{{ $message }}</div>
        @enderror

        <div class="form-group">
            <label for="videos">Videos</label>
            <input name="videos" id="videos" class="form-control" type="number" placeholder="0"   value="{{$channel->video_count}}">
        </div>

        @error('videos')
        <div class=" alert alert-danger">{{ $message }}</div>
        @enderror

        
        <div class="form-group">
            <label for="score">Channel Score</label>
            <input name="score" id="score" class="form-control" type="number" placeholder="0"    value="{{$channel->score}}">
        </div>

        @error('score')
        <div class=" alert alert-danger">{{ $message }}</div>
        @enderror

        <button type="submit" class="btn btn-secondary">Submit</button>
    </form>
    @endsection
</div>
