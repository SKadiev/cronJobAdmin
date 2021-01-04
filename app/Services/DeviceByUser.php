<?php
namespace App\Services;
use App\Models\Device;
use App\Models\User;
use DB;
class DeviceByUser

{

    public function deviceByUser (User $user)    {

        $roleForUser = ($user->role->name);
        switch ($roleForUser) {
            case 'admin':  return $this->adminDevices();

            default : return $this->devicesForRegularUser($user);
        }

        
    }

    private function adminDevices () {

        $devices = DB::table('devices')
            ->leftJoin('users', 'devices.user_id', '=', 'users.id')
            ->select('devices.type', 'devices.id', 'users.name')
            ->get();
            
        return $devices;

    }

    private function devicesForRegularUser (User $user) {
        return $user->devices;
    }


}