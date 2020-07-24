<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class UserController extends Controller
{
    public function __construct(){
        $this->middleware('auth');
    }
    public function index(Request $request){
        if(\request()->ajax()){
            $key = $request->get('query');
            $users = User::where('name','LIKE','%'.$key.'%')->orWhere('lastName','LIKE','%'.$key.'%')->where('type','AppUser')->get();
            return $users;
        }else{
            $users = User::where('type','AppUser')->orderBy('id',"DESC")->get();
            return view('admin.users.index')->with('users',$users);
        }
    }
}
