<?php

namespace App\Http\Controllers;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use App\Jobs\Mail\UserConfirmation;

class WebController extends ControllerWeb
{
    public function terminos(){
        return view('terminos');
    }
    public function email_verified($code){
        $user = User::where('email','germanruelas17@gmail.com')->first();
        dispatch(new UserConfirmation($user));
        return "yes";
        // if($user){
        //     User::where('code',$code)->update([
        //         'email_verified_at' => Carbon::now()
        //     ]);
        //     return view('emailverified');
        // }else{
        //     return view('emailnotverified');
        // }
    }
}
