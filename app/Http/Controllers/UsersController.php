<?php

namespace App\Http\Controllers;
use \Illuminate\View\View;
use \App\Tables\UsersTable;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Role;
use DB;
use Auth;
class UsersController extends Controller
{

    public function index () {

        $user = Auth::user();
        $this->authorize('viewAny',  $user);
        $userRole = $user->role->name;
        $users = DB::table('users')
        ->leftJoin('roles', 'users.roles_id', '=', 'roles.id')
        ->select('users.id', 'users.name as username' , 'roles.name as roletype')
        ->get();
        
        return view('user.index',  ['users' =>  $users,  "userRole" => $userRole]);
    }

    public function edit(User $user)
    {
        
        $roles = Role::pluck('name', 'id');
        return view('user.edit', (compact('user', 'roles')));

    }



    public function update(Request $request, User $user)
    {
        $user->update([

            "name" => request("name"),
            "roles_id" => request("roles_id")

        ]);

        return redirect()->action([UsersController::class, 'index']);

    }


    public function destroy( User $user) {

        $user->removeCascade();
        return redirect()->action([UsersController::class, 'index']);

    }

    
}
