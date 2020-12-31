<?php
namespace App\Facade;
use Illuminate\Support\Facades\Facade;
use App\Helpers\DeviceDetect;
class DeviceDetectFacade  extends Facade {

    protected static function getFacadeAccessor()
    {
        return DeviceDetect::class;
    }

}