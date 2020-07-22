<?php

namespace App\Http\Controllers\ApiRest;

use Socialite;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SocialController extends ApiController
{
    public function __construct(){
        $this->middleware('auth:api', ['only' => ['gmail']]);
    }
    public function facebook(Request $request) {
        Log::notice($request->all());
        $user = Socialite::driver('facebook')->userFromToken($request->access_token);

        if($user == null){
            return response()->json([
                "success" => false
            ]);
        }

        return response()->json([
            "success" => true,
            "token_type" => "Bearer",
            'access_token' => $request->access_token
        ]);
    }
    public function gmail(){

    }
}
