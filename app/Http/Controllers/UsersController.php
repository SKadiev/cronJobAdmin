<?php

namespace App\Http\Controllers;
use \Illuminate\View\View;
use \App\Tables\UsersTable;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
use Auth;
class UsersController extends Controller
{

    public function index () {
        $user = Auth::user();
        $userRole = $user->role->name;
        $users = DB::table('users')
        ->leftJoin('roles', 'users.roles_id', '=', 'roles.id')
        ->select('users.id', 'users.name as username' , 'roles.name as roletype')
        ->get();
        
        return view('user.index',  ['users' =>  $users,  "userRole" => $userRole]);
    }
}
