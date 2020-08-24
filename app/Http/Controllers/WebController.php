<?php

namespace App\Http\Controllers;

use Nexmo;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;

class WebController extends ControllerWeb
{
    public function terminos(){
        return view('terminos');
    }
    public function email_verified($code){
        $user = User::where('code',$code)->where('email_verified_at',null)->first();
        if($user){
            User::where('code',$code)->update([
                'email_verified_at' => Carbon::now()
            ]);
            return view('emailverified');
        }else{
            return view('emailnotverified');
        }
    }
    public function test(){
        Nexmo::message()->send([
            'to'   => '+51997491844',
            'from' => 'KlipHome',
            'text' => '2020 es tu numero de verificacion para KlipHome',
            'type' => 'text'
        ]);
        return "si";
    }
}
