<?php
namespace App\Services;
use App\Models\{User, Role};
class RoleByUser
{
   
    public function registerRoleId () : int {

        $users = User::all();
        return $users->isEmpty() ?
         Role::where('name' , '=', 'admin' )->first()->id :
         Role::where('name' , '=', 'moderator' )->first()->id;
    }

    

}