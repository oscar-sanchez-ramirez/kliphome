<?php

namespace App\Http\Controllers\ApiRest;

use App\Address;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\User;
use Illuminate\Support\Facades\Log;

class RegisterController extends Controller
{
    public function register(Request $request){
        $this->validate($request,[
            'email' => 'required|email|unique:users',
            'name' => 'required',
            // 'lastName' => 'required',
            'password' => 'required'
        ]);
        $user = User::create([
            'name' => $request->name,
            'lastName' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'password' => bcrypt($request->password),
        ])->toArray();
        Log::notice($user);
        Address::create([
            'alias' => $request->alias,
            'address' => $request->address,
            'user_id' => $user["id"]
        ]);
        return response()->json([
            'message' => "Usuario creado correctamente",
            'user' => $user
        ]);
    }
}
