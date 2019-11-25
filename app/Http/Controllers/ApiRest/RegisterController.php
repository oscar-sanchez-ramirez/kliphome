<?php

namespace App\Http\Controllers\ApiRest;

use App\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;

class RegisterController extends Controller
{
    public function register(Request $request){
        $this->validate($request,[
            'email' => 'required|email|unique:users',
            'name' => 'required',
            'last_name' => 'required',
            'password' => 'required'
        ]);
        $user = User::create([
            'name' => $request->name,
            'last_name' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ]);
        $address = Address::create([
            'alias' => $request->alias,
            'address' => $request->address,
            'user_id' => $user->id
        ]);
        return response()->json([
            'message' => "Usuario creado correctamente",
            'user' => $user->toArray()
        ]);
    }
}
