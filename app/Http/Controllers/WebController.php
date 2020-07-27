<?php

namespace App\Http\Controllers;
use App\User;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Nexmo\Laravel\Facade\Nexmo;

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
        $num = (string)("51997491844");
        Nexmo::message()->send([
            'to'   => $num,
            'from' => 'KlipHome',
            'text' => '227589 es tu número de verificación para KlipHome',
            'type' => 'text'
        ]);
    }
}
