<?php

namespace App\Http\Controllers\ApiRest;

use App\User;
use Socialite;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SocialController extends ApiController
{
    public function __construct(){
        // $this->middleware('auth:api', ['only' => ['gmail','checkifexists']]);
    }
    public function facebook(Request $request) {
        Log::notice($request->all());
        try {
            $user = Socialite::driver('facebook')->userFromToken($request->access_token);

            if($user == null){
                return response()->json([
                    "success" => false,
                    "message" => 'No se encontro al usuario, inténte con otra cuenta'
                ]);
            }else{
                $user = $this->checkifexists($user);
                return response()->json([
                    "success" => true,
                    "user" => $user
                ]);
            }


        } catch (\Throwable $th) {
            return response()->json([
                "success" => false,
                "message" => 'Hubo un error inesperado, inténtelo más tarde'
            ]);
        }

    }
    public function checkifexists($user){
        $check_user = User::where('email',$user->email)->first();
        if(!$check_user){
            $random = strtoupper(substr(md5(mt_rand()), 0, 10));
            $user = User::create([
                'name' => $user->name,
                'email' => $user->email,
                'password' => bcrypt($random),
                'code' => $random
            ])->toArray();
            // dispatch(new UserConfirmation($user));
        }else{
            return $check_user;
        }
    }
    public function gmail(){

    }
}
