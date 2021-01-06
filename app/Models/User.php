<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'name',
        'email',
        'password',
        'roles_id'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    public function devices () {
        return $this->hasMany(Device::class);
    }

    public function role () {
        return $this->hasOne(Role::class, 'id');
    }

    public function roleAuthorizationForUser () {

        $role = $this->hasOne(Role::class, 'id')->first()->name;
        switch ($role) {
            case 'admin': return true;
            default: return false;

        }
        
    }

    public function removeCascade () {

        $this->removePagesCascade();
        $this->delete();
    }

    public function removePagesCascade() {

        $reletedDevices = $this->devices()->get();
        if (count($reletedDevices) > 0) {
            foreach ($reletedDevices as $device) {
                $device->delete();
            }

        }
    }

   
}
