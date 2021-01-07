@extends('layouts.app')

@section('content')
<div class="container">
    <a href="/channel/create" class="btn btn-secondary  mb-2 mr-2 pull-right" role="button">Create Channel</a>
    <a href="#" class="btn btn-secondary  mb-2 mr-2 pull-right" role="button">Import Channels</a>

    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">Channel Name</th>
                <th scope="col">Channel Username</th>
                <th scope="col">Channel Id</th>
                <th scope="col">Subscribers</th>
                <th scope="col">Views</th>
                <th scope="col">Videos</th>
                <th scope="col">Score</th>
                <th scope="col">Edit</th>
                <th scope="col">Delete</th>

            </tr>
        </thead>
        <tbody>

            @foreach($channels as $key => $channel)

            <tr>
                <td>{{$channel->channel_name}}</td>
                <td>{{$channel->channel_username}}</td>
                <td>{{$channel->channel_id}}</td>
                <td>{{$channel->subscribers}}</td>
                <td>{{$channel->views_count}}</td>
                <td>{{$channel->video_count}}</td>
                <td>{{$channel->score}}</td>
                <td><a href="/channel/{{$channel->id}}/edit" class="btn btn-secondary " role="button">Edit Channel</a></td>
                
                {{-- @can('delete-domain', $domain) --}}

                <td>
                    <form method="POST" action="/channel/{{$channel->id}}">
                        @csrf()
                        @method('DELETE')
                        <button onclick="return confirm('Are you sure? You will lose data')" type="submit" class="btn btn-secondary">Delete</button>

                    </form>
                </td>
                
                {{-- @endcan --}}


            </tr>
            @endforeach

        </tbody>
    </table>

    {{$channels->links()}}
</div>
@endsection
