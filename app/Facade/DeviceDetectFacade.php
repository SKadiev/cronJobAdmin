<?php
namespace App\Facade;
use Illuminate\Support\Facades\Facade;
use App\Services\DeviceDetect;


/**
 
 * @method static void resolveOrCreateDevice(string $device_uuid)
 *
 *@see \App\Services\DeviceDetect

 */

class DeviceDetectFacade  extends Facade {

    protected static function getFacadeAccessor()
    {
        return DeviceDetect::class;
    }

}