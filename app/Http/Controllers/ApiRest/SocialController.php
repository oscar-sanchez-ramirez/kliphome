<?php

namespace App\Http\Controllers\ApiRest;

use Socialite;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SocialController extends ApiController
{
    public function facebook(Request $request) {
        Log::notice($request->all());
        $user = Socialite::driver('facebook')->userFromToken( $request->input('accessToken'));
        Log::notice($user);
        // abort_if($user == null || $user->id != $request->input('userID'),400,'Invalid credentials');

        // // get existing user or create new (find by facebook_id or create new record)
        // $user = ....

        // return $this->issueToken($user);
    }
}
