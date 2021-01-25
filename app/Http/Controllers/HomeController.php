<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DeviceDetect;
use Illuminate\Support\Facades\Redis;
class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Contracts\Support\Renderable
     */
    public function index(Request $request)
    {
     
        DeviceDetect::resolveOrCreateDevice('dsaaaaasds',  Auth::user()->id);

        return view('home');
    }
}
