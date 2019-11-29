<?php

namespace App\Http\Controllers\ApiRest;

use App\Http\Controllers\ApiController;
use Illuminate\Http\Request;

class FixerManController extends ApiController
{
    public function register(Request $request){
        Log::info('registrando worker');
        // $this->validate($request,[
        //     'email' => 'required|email|unique:users',
        //     'name' => 'required',
        //     // 'lastName' => 'required',
        //     'password' => 'required'
        // ]);
        $user = User::create([
            'name' => $request->name,
            'lastName' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'type' => 'AppFixerMan',
            'state' => 0,
            'password' => bcrypt($request->password),
        ])->toArray();
        Log::notice($request->all());
        // Address::create([
        //     'alias' => $request->alias,
        //     'address' => $request->address,
        //     'user_id' => $user["id"]
        // ]);
        return response()->json([
            'message' => "Usuario creado correctamente",
            'user' => $user
        ]);
    }
}
