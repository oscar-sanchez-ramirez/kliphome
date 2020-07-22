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
        $user = Socialite::driver('facebook')->userFromToken('EAACqlQmudnUBAME6bGV56LDFkyrLigy01CWGpns6n2RZBJMb45ZCZBvEy1oR5Pq4awSjZBqZBaOT7rVjYn4HaOE5DKSPIYRK5gCihxGdPZBx00bjXr3l87VSG5VxPZAz2aZAdHHeDQwnKIQa2XIGVQWCT4TYgIEM9CmKbmGTmTZAEFXFNB9hu5NQK');
        return $user->setToken('EAACqlQmudnUBAME6bGV56LDFkyrLigy01CWGpns6n2RZBJMb45ZCZBvEy1oR5Pq4awSjZBqZBaOT7rVjYn4HaOE5DKSPIYRK5gCihxGdPZBx00bjXr3l87VSG5VxPZAz2aZAdHHeDQwnKIQa2XIGVQWCT4TYgIEM9CmKbmGTmTZAEFXFNB9hu5NQK');
        // abort_if($user == null || $user->id != $request->input('userID'),400,'Invalid credentials');

        // // get existing user or create new (find by facebook_id or create new record)
        // $user = ....

        // return $this->issueToken($user);
    }
    public function gmail(){

    }
}
