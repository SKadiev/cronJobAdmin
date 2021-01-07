@extends('layouts.app')

@section('content')
<div class="container">
    <table class="table table-dark">
        <thead>
            <tr>
                <th scope="col">Video Title</th>
                <th scope="col">Published By</th>
                <th scope="col">Url</th>
                <th scope="col">Embed Code</th>
                <th scope="col">Views</th>
                <th scope="col">Comments</th>
                <th scope="col">Likes</th>
                <th scope="col">Dislikes</th>
                <th scope="col">Favorite</th>
            </tr>
        </thead>
        <tbody>
            @foreach($videos as $key => $video)
            <tr>
                <td>{{$video->title}}</td>
                <td>{{$video->published_by}}</td>
                <td>{{$video->url}}</td>
                <td>{{$video->embed}}</td>
                <td>{{$video->views}}</td>
                <td>{{$video->comments}}</td>
                <td>{{$video->likes}}</td>
                <td>{{$video->dislikes}}</td>
                <td>{{$video->favorite}}</td>
                
            </tr>
            @endforeach
            
        </tbody>
    </table>
    {{$videos->links()}}
</div>
@endsection
