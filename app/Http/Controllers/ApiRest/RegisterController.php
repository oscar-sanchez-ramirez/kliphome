<?php

namespace App\Http\Controllers\ApiRest;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class RegisterController extends Controller
{
    public function register(Request $request){
        $this->validate($request,[
            'email' => 'required|email|unique:users',
            'name' => 'required',
            'password' => 'required'
        ]);
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        return response()->json([
            'message' => "Usuario creado correctamente",
            'user' => $user->toArray()
        ]);
    }
}
