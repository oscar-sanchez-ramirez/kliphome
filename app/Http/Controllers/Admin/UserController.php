<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    //
    public function index(){
        $users = User::where('type','AppUser')->get();
        return view('admin.users.index')->with('users',$users);
    }
}
