<?php

namespace App\Http\Controllers\ApiRest;

use App\Http\Controllers\ApiController;
use App\SelectedDelegation;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

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
        Log::notice($request->all());
        $user = User::create([
            'name' => $request->name,
            'lastName' => $request->last_name,
            'phone' => $request->phone,
            'email' => $request->email,
            'type' => 'AppFixerMan',
            'state' => 0,
            'password' => bcrypt($request->password),
        ])->toArray();
        $selected = new SelectedDelegation;
        $selected->user_id = $user["id"];
        $selected->delegation_id = $request->workarea;
        $selected->save();

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
