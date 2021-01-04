<?php

namespace App\Http\Controllers;

use App\Models\Device;
use Auth;
use Illuminate\Http\Request;
use App\Services\DeviceByUser;
class DevicesController extends Controller
{

    protected $deviceByUserService;


    public function __construct(DeviceByUser $deviceByUserService) {

        $this->deviceByUserService = $deviceByUserService ;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response    
     */
    public function index()
    {
        $user = Auth::user();
        $userRole = $user->role->name;
        $devicesForUser = $this->deviceByUserService->deviceByUser($user);
        return view("device.index",["devices" => $devicesForUser, "userRole" => $userRole]);

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function show(Device $device)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function edit(Device $device)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Device $device)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Device  $device
     * @return \Illuminate\Http\Response
     */
    public function destroy(Device $device)
    {
        $device->delete();
        return redirect()->action([DevicesController::class, 'index']);
    }
}
