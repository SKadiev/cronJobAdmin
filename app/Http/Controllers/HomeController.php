<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Auth;
use DeviceDetect;
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
    public function index()
    {
        dd( Auth::user()->devices()->get());

        DeviceDetect::resolveOrCreateDevice('dsasds',  Auth::user()->id);

       
        return view('home');
    }
}
