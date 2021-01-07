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

        return redirect()->action([YoutubeChannelsController::class, 'index']);
    }


    public function destroy(YoutubeChannel $channel) {
        
        $channel->delete();
        return redirect()->action([YoutubeChannelsController::class, 'index']);
    }


    public function edit(YoutubeChannel $channel)
    {

        return view('channel.edit', ["channel"=> $channel]);
    }

   
    public function update(Request $request, YoutubeChannel $channel)
    {
        // dd($request);
        $channel->update([

            "channel_name" => request("channel_name"),
            "channel_username" =>request("channel_username"),
            "channel_id" =>request("channel_id"),
            "subscribers" =>request("subscribers"),
            "views_count" =>request("views"),
            "video_count" =>request("videos"),
            "score" =>request("score")

        ]);

        return redirect()->action([YoutubeChannelsController::class, 'index']);

    }
}
