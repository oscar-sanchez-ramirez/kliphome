<?php

namespace App\Http\Controllers;

use Nexmo;
use App\User;
use Carbon\Carbon;
use App\TempPayment;
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
        // sleep(1);
        $revisar_pagos_previos = TempPayment::where('user_id',733)->where('price',"50")->first();
        if($revisar_pagos_previos){
            return response()->json([
                'success' => true,
                'message' => "Pago exitoso",
            ]);
        }else{
            return 2;
        }
        // Nexmo::message()->send([
        //     'to'   => '+51997491844',
        //     'from' => 'KlipHome',
        //     'text' => '2020 es tu numero de verificacion para KlipHome',
        //     'type' => 'text'
        // ]);
        // return "si";
    }
}
