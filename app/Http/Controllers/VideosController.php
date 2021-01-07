<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Video;

class VideosController extends Controller
{
    public function index()
    {

        return view("videos.index",["videos" => Video::all()]);
    }
}
