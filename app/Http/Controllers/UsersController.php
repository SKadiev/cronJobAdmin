<?php

namespace App\Http\Controllers;
use \Illuminate\View\View;
use \App\Tables\UsersTable;
use Illuminate\Http\Request;
use App\Models\User;
use DB;
class UsersController extends Controller
{

    public function index () {

        $users = DB::table('users')
        ->leftJoin('roles', 'users.roles_id', '=', 'roles.id')
        ->select('users.id', 'users.name as username' , 'roles.name as roletype')
        ->get();
        
        return view('user.index',  ['users' =>  $users]);
    }
}
