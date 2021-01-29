<?php

namespace App\Http\Controllers;

use Mail;
use App\User;
use Carbon\Carbon;
use App\AdminCoupon;
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
        // $usuario = array('name' => "error", 'tipo' => "PGO",'error'=> "Errorcillo");
        // // $mail = ["tonyhamui68@gmail.com","kliphomegaby@gmail.com"];
        // $mail = ["germanruelas17@gmail.com","german.ruelas@tigears.com"];
        // // $mail = "germanruelas17@gmail.com";
        // Mail::send('emails.conektaerror',$usuario, function($msj) use ($mail){
        //     $msj->subject('KlipHome: Error en conekta');
        //     $msj->to($mail,"Datos del error Conekta");
        // });
    }
}
