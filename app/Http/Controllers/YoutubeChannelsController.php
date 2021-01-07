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
}
