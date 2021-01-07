<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\YoutubeChannel;

class YoutubeChannelsController extends Controller
{
    public function index () {
        return view("channel.index",["channels" => YoutubeChannel::paginate(5)]);
    }

    public function create()
    {

        return view("channel.create");
    }

    public function store(Request $request)
    {

        
        $channelData = ($request->validate([
            'channel_name' => 'required|max:255',
            'channel_username' => 'required|max:255',
            'channel_id' => 'required|max:255',
            
            ]));
            
        $channelData['subscribers'] = request('subscribers');
        $channelData['views_count'] = request('views');
        $channelData['video_count'] = request('videos');
        $channelData['score'] = request('score');



        YoutubeChannel::create(
            $channelData
        );

        return redirect('channel');
    }
}
