<?php
namespace App\Helpers;
use App\Models\Device;
class DeviceDetect
{
    protected $deviceInDb;
    protected $curentDevice;

    

    public function resolveOrCreateDevice ($device_uuid, $user_id) : void {

        $this->deviceDetect($device_uuid);

        if (!$this->deviceExist()) {
            
            $newDevice =  Device::create([
                'uuid' => $device_uuid,
                'type' => 'browser',
                'user_id' => $user_id
            ]);
            
            $this->curentDevice = $newDevice;

        }  else {

            $this->curentDevice = $this->deviceInDb;
        }


        $this->curentDevice->id;
        
    }


    private function deviceDetect ($device_uuid) {

        $this->deviceInDb =  Device::where('uuid', $device_uuid)->first();

    }

    private function deviceExist ()  {

        return $this->deviceInDb;
    }

    public function deviceId() : string {

       return  $this->curentDevice->id;
    }
}