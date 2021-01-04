<?php
namespace App\Services;
use App\Models\Device;
use App\Models\User;

class DeviceByUser

{

    public function deviceByUser (User $user)    {

        $roleNameForUser = ($user->roleName->name);

        switch ($roleNameForUser) {
            case 'admin':  return $this->adminDevices();

            default : return $this->devicesForRegularUser($user);
        }

        
    }

    private function adminDevices () {
        return Device::all();

    }

    private function devicesForRegularUser (User $user) {
        return $user->devices;
    }


}