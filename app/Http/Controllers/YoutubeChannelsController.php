<?php

namespace App\Http\Controllers;
use Illuminate\Support\Arr;
use Illuminate\Http\Request;
use App\Models\YoutubeChannel;
use App\Services\ChannelsFromCsv;
class YoutubeChannelsController extends Controller
{
    public function index () {
        return view("channel.index",["channels" => YoutubeChannel::paginate(5)]);
    }

    public function create()
    {

        return view("channel.create");
    }

    public function store(Request $request, ChannelsFromCsv $channelService)
    {

        $channelData = ($request->validate([
            'channel_url' => 'max:255',
        ]));

        $channels = $channelService->parseChannel([$channelData['channel_url']]);
        
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


    public function importChannels (ChannelsFromCsv $channelService) {

        if ($f_pointer = fopen($_FILES['csv_data']['tmp_name'], 'r')) {
            $temp = [];
            
            while (! feof($f_pointer)) {
                $payload = fgetcsv($f_pointer);
                
                if( is_array($payload) ) {
                    array_push($temp,$payload[0]);
                }
                
            }
            
            $list = $channelService->parseChannels($temp);

            }
            fclose($f_pointer);

    } 

    
}
